<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Utils;

class Utils
{
    public static function configureSecurityClient(\GuzzleHttp\ClientInterface $client, mixed $security): \GuzzleHttp\ClientInterface
    {
        $sec = new Security();
        $clientOptions = $sec->parseSecurity($security);

        return new SecurityClient($client, $clientOptions);
    }

    public static function replaceParameters(string $url, mixed $params): string
    {
        $url = preg_replace_callback('/{([^}]+)}/', function ($matches) use ($params) {
            $key = $matches[1];
            if (array_key_exists($key, $params)) {
                return $params[$key];
            } else {
                return $matches[0];
            }
        }, $url);

        return $url;
    }

    public static function generateURL(string $url, string $path, mixed $pathParams = null): string
    {
        $url = remove_suffix($url, "/") . $path;

        $pp = new PathParameters();
        $params = $pp->parsePathParams($pathParams);

        return Utils::replaceParameters($url, $params);
    }

    public static function matchContentType(string $contentType, string $pattern): bool
    {
        if ($contentType === $pattern || $pattern === "*" || $pattern === "*/*") {
            return true;
        }

        $mediaType = strtolower(trim(explode(";", $contentType)[0]));

        if ($mediaType === $pattern) {
            return true;
        }

        $parts = explode("/", $mediaType);
        if (count($parts) === 2) {
            $type = $parts[0];
            $subtype = $parts[1];

            if ($pattern === "$type/*" || $pattern === "*/$subtype") {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $request
     * @return array<string, mixed>|null
     */
    public static function serializeRequestBody(mixed $request): array | null
    {
        $rb = new RequestBodies();
        $body = $rb->serializeRequestBody($request);

        if ($body === null) {
            return [];
        }

        return $body;
    }

    /**
     * @param mixed $queryParams
     * @return array<string, mixed>
     */
    public static function getQueryParams(mixed $queryParams): array
    {
        $qp = new QueryParameters();

        $query = $qp->parseQueryParams($queryParams);

        if ($query === null) {
            return [];
        }

        return [
            "query" => $query,
        ];
    }

    /**
     * @param mixed $headers
     * @return array<string, mixed>
     */
    public static function getHeaders(mixed $headers): array
    {
        $h = new Headers();

        $headers = $h->parseHeaders($headers);

        return [
            "headers" => $headers,
        ];
    }
}

function remove_prefix(string $text, string $prefix): string
{
    if (0 === strpos($text, $prefix))
        $text = substr($text, strlen($prefix));
    return $text;
}

function remove_suffix(string $text, string $suffix): string
{
    $suffix_length = strlen($suffix);
    if (substr($text, -$suffix_length) === $suffix) {
        return substr($text, 0, -$suffix_length);
    }
    return $text;
}

function valToString(mixed $val, string $dateTimeFormat = ''): string
{
    switch (gettype($val)) {
        case 'string':
            return $val;
        case 'object':
            switch (get_class($val)) {
                case 'DateTime':
                    if (empty($dateTimeFormat)) {
                        $dateTimeFormat = 'Y-m-d\TH:i:s.uP';
                    }

                    return $val->format($dateTimeFormat);
                default:
                    if (is_a($val, \BackedEnum::class, true)) {
                        $enumVal = $val->value;
                        if (is_string($enumVal)) {
                            return $enumVal;
                        }

                        return var_export($enumVal, true);
                    }

                    return var_export($val, true);
            }
        default:
            return var_export($val, true);
    }
}

