<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\utils;

use ReflectionProperty;

class Headers
{
    /**
     * @param mixed $headers
     * @return array<string, string>
     */
    public function parseHeaders(mixed $headers): array
    {
        $out = [];

        if (is_null($headers)) {
            return $out;
        }

        foreach ($headers as $field => $value) {
            if (is_null($value)) {
                continue;
            }

            $metadata = $this->parseHeaderMetadata(new ReflectionProperty(get_class($headers), $field));
            if (is_null($metadata)) {
                continue;
            }

            $value = $this->serializeHeader($metadata, $value);

            if (empty($value)) {
                continue;
            }

            $out[$metadata->name] = $value;
        }

        return $out;
    }

    private function serializeHeader(ParamsMetadata $metadata, mixed $value): string
    {
        switch (gettype($value)) {
            case 'object':
                $items = [];

                foreach ($value as $field => $fieldValue) { /** @phpstan-ignore-line */
                    if (is_null($fieldValue)) {
                        continue;
                    }

                    $fieldMetadata = $this->parseHeaderMetadata(new ReflectionProperty(get_class($value), $field));
                    if (is_null($fieldMetadata) || empty($fieldMetadata->name)) {
                        continue;
                    }

                    if ($metadata->explode) {
                        $items[] = $fieldMetadata->name . '=' . valToString($fieldValue);
                    } else {
                        $items[] = $fieldMetadata->name;
                        $items[] = valToString($fieldValue);
                    }
                }

                return implode(',', $items);
            case 'array':
                if (array_is_list($value)) {
                    return implode(',', array_map(fn ($v) => valToString($v), $value));
                } else {
                    $items = [];

                    foreach ($value as $field => $fieldValue) {
                        if (is_null($fieldValue)) {
                            continue;
                        }

                        if ($metadata->explode) {
                            $items[] = $field . '=' . valToString($fieldValue);
                        } else {
                            $items[] = $field;
                            $items[] = valToString($fieldValue);
                        }
                    }

                    return implode(',', $items);
                }
            default:
                return valToString($value);
        }
    }

    private function parseHeaderMetadata(ReflectionProperty $property): ParamsMetadata | null
    {
        $metadataStr = SpeakeasyMetadata::find($property->getAttributes(SpeakeasyMetadata::class), "header");
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
