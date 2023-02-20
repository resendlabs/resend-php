<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\utils;

use ReflectionProperty;

class QueryParameters
{
    /**
     * @param mixed $queryParams
     * @return string
     */
    public function parseQueryParams(mixed $queryParams): string | null
    {
        $parts = [];

        if (is_null($queryParams)) {
            return null;
        }

        foreach ($queryParams as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $metadata = $this->parseQueryParamsMetadata(new ReflectionProperty(get_class($queryParams), $field));
            if (is_null($metadata)) {
                continue;
            }

            if (!empty($metadata->serialization)) {
                $parts[] = $this->parseSerializationParams($metadata, $value);
            } else {
                switch ($metadata->style) {
                    case "deepObject":
                        $parts[] = $this->parseDeepObjectParams($metadata, $value);
                        break;
                    case "form":
                        $parts[] = $this->parseFormParams($metadata, $value);
                        break;
                    default:
                        throw new \Exception("Unsupported style: " . $metadata->style);
                }
            }
        }

        return empty($parts) ? null : implode("&", $parts);
    }

    /**
     * @param ParamsMetadata $metadata
     * @param mixed $value
     * @return string
     */
    private function parseSerializationParams(ParamsMetadata $metadata, mixed $value): string
    {
        $queryParams = [];

        switch ($metadata->serialization) {
            case "json":
                $serializer = JSON::createSerializer();
                $queryParams[$metadata->name] = $serializer->serialize($value, 'json');
                break;
            default:
                throw new \Exception("Unsupported serialization: " . $metadata->serialization);
        }

        return http_build_query($queryParams);
    }

    /**
     * @param ParamsMetadata $metadata
     * @param mixed $value
     * @return string
     */
    private function parseDeepObjectParams(ParamsMetadata $metadata, mixed $value): string
    {
        $queryParams = [];

        $dateTimeFormat = $metadata->dateTimeFormat;

        switch (gettype($value)) {
            case "object":
                foreach ($value as $field => $val) { /** @phpstan-ignore-line */
                    if (is_null($val)) {
                        continue;
                    }

                    $fieldMetaData = $this->parseQueryParamsMetadata(new ReflectionProperty(get_class($value), $field));
                    if (is_null($fieldMetaData)) {
                        continue;
                    }

                    $dateTimeFormat = $fieldMetaData->dateTimeFormat;

                    $items = [];

                    if (gettype($val) == "array" && array_is_list($val)) {
                        foreach ($val as $item) {
                            $items[] = valToString($item, $dateTimeFormat);
                        }
                    } else {
                        $queryParams[$metadata->name . "[" . $fieldMetaData->name . "]"] = valToString($val, $dateTimeFormat);
                    }

                    if (count($items) > 0) {
                        $queryParams[$metadata->name . "[" . $fieldMetaData->name . "]"] = $items;
                    }
                }
                break;
            case "array":
                if (!array_is_list($value)) {
                    foreach ($value as $key => $val) {
                        if (is_null($val)) {
                            continue;
                        }

                        $items = [];

                        if (gettype($val) == "array" && array_is_list($val)) {
                            foreach ($val as $item) {
                                $items[] = valToString($item, $dateTimeFormat);
                            }
                        } else {
                            $queryParams[$metadata->name . "[" . $key . "]"] = valToString($val, $dateTimeFormat);
                        }

                        if (count($items) > 0) {
                            $queryParams[$metadata->name . "[" . $key . "]"] = $items;
                        }
                    }
                }
                break;
        }

        return $this->buildQueryString($queryParams);
    }

    /**
     * @param ParamsMetadata $metadata
     * @param mixed $value
     * @return string
     */
    private function parseFormParams(ParamsMetadata $metadata, mixed $value): string
    {
        $queryParams = [];

        $dateTimeFormat = $metadata->dateTimeFormat;

        switch (gettype($value)) {
            case "object":
                $items = [];

                foreach ($value as $field => $val) { /** @phpstan-ignore-line */
                    if (is_null($val)) {
                        continue;
                    }

                    $fieldMetaData = $this->parseQueryParamsMetadata(new ReflectionProperty(get_class($value), $field));
                    if (is_null($fieldMetaData)) {
                        continue;
                    }

                    $dateTimeFormat = $fieldMetaData->dateTimeFormat;

                    if ($metadata->explode) {
                        $queryParams[$fieldMetaData->name] = valToString($val, $dateTimeFormat);
                    } else {
                        $items[] = $fieldMetaData->name . "," . valToString($val, $dateTimeFormat);
                    }
                }

                if (count($items) > 0) {
                    $queryParams[$metadata->name] = implode(",", $items);
                }
                break;
            case "array":
                if (array_is_list($value)) {
                    $values = [];
                    $items = [];

                    foreach ($value as $item) {
                        if ($metadata->explode) {
                            $values[] = valToString($item, $dateTimeFormat);
                        } else {
                            $items[] = valToString($item, $dateTimeFormat);
                        }
                    }

                    if (count($items) > 0) {
                        $values[] = implode(",", $items);
                    }

                    $queryParams[$metadata->name] = $values;
                } else {
                    $items = [];

                    foreach ($value as $key => $val) {
                        if (is_null($val)) {
                            continue;
                        }

                        if ($metadata->explode) {
                            $queryParams[$key] = valToString($val, $dateTimeFormat);
                        } else {
                            $items[] = $key . "," . valToString($val, $dateTimeFormat);
                        }
                    }

                    if (count($items) > 0) {
                        $queryParams[$metadata->name] = implode(",", $items);
                    }
                }
                break;
            default:
                $queryParams[$metadata->name] = valToString($value, $dateTimeFormat);
        }

        return $this->buildQueryString($queryParams);
    }

    /**
     * @param array<array<string>|string> $queryParams
     */
    private function buildQueryString(array $queryParams): string
    {
        ksort($queryParams);

        $parts = [];
        foreach ($queryParams as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $parts[] = urlencode($key) . "=" . urlencode($item);
                }
            } else {
                $parts[] = urlencode($key) . "=" . urlencode($value);
            }
        }

        return implode("&", $parts);
    }

    private function parseQueryParamsMetadata(ReflectionProperty $property): ParamsMetadata | null
    {
        $metadataStr = SpeakeasyMetadata::find($property->getAttributes(SpeakeasyMetadata::class), "queryParam");
        if (is_null($metadataStr)) {
            return null;
        }

        $metadata = ParamsMetadata::parse($metadataStr);
        if (is_null($metadata)) {
            return null;
        }

        return $metadata;
    }
}

