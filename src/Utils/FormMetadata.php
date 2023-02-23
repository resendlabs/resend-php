<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Utils;

class FormMetadata
{
    public string $name;
    public bool $json;
    public string $style;
    public bool $explode;
    public string $dateTimeFormat;

    public static function parse(string $metadata): FormMetadata | null
    {
        if (!str_starts_with($metadata, "form:")) {
            return null;
        }

        $metadata = remove_prefix($metadata, "form:");

        $name = "";
        $json = false;
        $style = "form";
        $explode = true;
        $dateTimeFormat = "";

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
                case "json":
                    $json = $parts[1] === "true";
                    break;
                case "style":
                    $style = $parts[1];
                    break;
                case "explode":
                    $explode = $parts[1] === "true";
                    break;
                case "dateTimeFormat":
                    $dateTimeFormat = $parts[1];
                    break;
            }
        }

        return new FormMetadata($name, $json, $style, $explode, $dateTimeFormat);
    }

    private function __construct(string $name, bool $json, string $style, bool $explode, string $dateTimeFormat)
    {
        $this->name = $name;
        $this->json = $json;
        $this->style = $style;
        $this->explode = $explode;
        $this->dateTimeFormat = $dateTimeFormat;
    }
}