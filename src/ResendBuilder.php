<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK;

class ResendBuilder
{
    private ?\GuzzleHttp\ClientInterface $client;
    private ?models\shared\Security $security;
    private string $serverUrl;
    /** @var array<string, string> */
    private ?array $params;

    public function __construct() {
        $this->client = null;
        $this->security = null;
        $this->serverUrl = '';
        $this->params = null;
    }

    public function setClient(\GuzzleHttp\ClientInterface $client): ResendBuilder
    {
        $this->client = $client;
        return $this;
    }
    
    public function setSecurity(models\shared\Security $security): ResendBuilder
    {
        $this->security = $security;
        return $this;
    }
    
    /**
    * Set the server URL and any parameters to interpolate into the URL.
    * @param string $serverUrl
    * @param array<string, string> $params
    * @return ResendBuilder
    */
    public function setServerURL(string $serverUrl, ?array $params = null): ResendBuilder
    {
        $this->serverUrl = $serverUrl;
        if (!is_null($params)) {
            $this->params = $params;
        }
        return $this;
    }
    
    public function build(): Resend
    {
        return new Resend($this->client, $this->security, $this->serverUrl, $this->params);
    }
}