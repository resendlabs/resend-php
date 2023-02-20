<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\utils;

use ReflectionProperty;

class PathParameters
{
    /**
     * @param mixed $pathParams
     * @return array<string, string>
     */
    public function parsePathParams(mixed $pathParams): array
    {
        $parsed = [];

        if (is_null($pathParams)) {
            return $parsed;
        }

        foreach ($pathParams as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $metadata = $this->parsePathParamsMetadata(new ReflectionProperty(get_class($pathParams), $field));
            if (is_null($metadata)) {
                continue;
            }

            switch ($metadata->style) {
                case "simple":
                    $parsed = array_merge_recursive($parsed, $this->parseSimplePathParams($metadata, $value));
                    break;
                default:
                    throw new \Exception("Unsupported style: " . $metadata->style);
            }
        }

        return $parsed;
    }

    /**
     * @param ParamsMetadata $metadata
     * @param mixed $value
     * @return array<string, string>
     */
    private function parseSimplePathParams(ParamsMetadata $metadata, mixed $value): array
    {
        $pathParams = [];

        switch (gettype($value)) {
            case "object":
                $vals = [];

                foreach ($value as $field => $fieldValue) { /** @phpstan-ignore-line */
                    if (is_null($fieldValue)) {
                        continue;
                    }

                    $fieldMetadata = $this->parsePathParamsMetadata(new ReflectionProperty(get_class($value), $field));
                    if (is_null($fieldMetadata)) {
                        continue;
                    }

                    if ($metadata->explode) {
                        $vals[] = sprintf("%s=%s", $fieldMetadata->name, valToString($fieldValue));
                    } else {
                        $vals[] = sprintf("%s,%s", $fieldMetadata->name, valToString($fieldValue));
                    }
                }

                $pathParams[$metadata->name] = implode(",", $vals);
                break;
            case "array":
                $vals = [];

                if (array_is_list($value)) {
                    foreach ($value as $val) {
                        $vals[] = valToString($val);
                    }
                } else {
                    foreach ($value as $key => $val) {
                        if ($metadata->explode) {
                            $vals[] = sprintf("%s=%s", $key, valToString($val));
                        } else {
                            $vals[] = sprintf("%s,%s", $key, valToString($val));
                        }
                    }
                }

                $pathParams[$metadata->name] = implode(",", $vals);
                break;
            default:
                $pathParams[$metadata->name] = valToString($value);
                break;
        }

        return $pathParams;
    }

    private function parsePathParamsMetadata(ReflectionProperty $property): ParamsMetadata | null
    {
        $metadataStr = SpeakeasyMetadata::find($property->getAttributes(SpeakeasyMetadata::class), "pathParam");
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
