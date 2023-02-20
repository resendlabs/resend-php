<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\utils;

class SecurityMetadata
{
    public bool $option;
    public bool $scheme;
    public string $name;
    public string $type;
    public string $subtype;

    public static function parse(string $metadata): SecurityMetadata | null
    {
        if (!str_starts_with($metadata, "security:")) {
            return null;
        }

        $metadata = remove_prefix($metadata, "security:");

        $option = false;
        $scheme = false;
        $name = "";
        $type = "";
        $subtype = "";

        $options = explode(",", $metadata);

        foreach ($options as $opt) {
            $parts = explode("=", $opt);
            if (count($parts) < 1 || count($parts) > 2) { /** @phpstan-ignore-line */
                continue;
            }

            switch ($parts[0]) {
                case "name":
                    $name = $parts[1];
                    break;
                case "type":
                    $type = $parts[1];
                    break;
                case "subtype":
                    $subtype = $parts[1];
                    break;
                case "option":
                    $option = true;
                    break;
                case "scheme":
                    $scheme = true;
                    break;
            }
        }

        return new SecurityMetadata($option, $scheme, $name, $type, $subtype);
    }

    private function __construct(bool $option, bool $scheme, string $name, string $type, string $subtype)
    {
        $this->option = $option;
        $this->scheme = $scheme;
        $this->name = $name;
        $this->type = $type;
        $this->subtype = $subtype;
    }
}
