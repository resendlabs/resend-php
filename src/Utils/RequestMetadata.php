<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Utils;

class RequestMetadata
{
    public string $mediaType;

    public static function parse(string $metadata): RequestMetadata | null
    {
        if (!str_starts_with($metadata, "request:")) {
            return null;
        }

        $metadata = remove_prefix($metadata, "request:");

        $mediaType = "";

        $options = explode(",", $metadata);

        foreach ($options as $opt) {
            $parts = explode("=", $opt); 
            if (count($parts) < 1 || count($parts) > 2) { /** @phpstan-ignore-line */
                continue;
            }

            switch ($parts[0]) {
                case "mediaType":
                    $mediaType = $parts[1];
                    break;
            }
        }

        return new RequestMetadata($mediaType);
    }

    private function __construct(string $mediaType)
    {
        $this->mediaType = $mediaType;
    }
}
