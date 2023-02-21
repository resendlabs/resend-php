<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK;

class Email 
{
	// SDK private variables namespaced with _ to avoid conflicts with API models
	private \GuzzleHttp\ClientInterface $_defaultClient;
	private \GuzzleHttp\ClientInterface $_securityClient;
	private string $_serverUrl;
	private string $_language;
	private string $_sdkVersion;
	private string $_genVersion;

	public function __construct(\GuzzleHttp\ClientInterface $defaultClient, \GuzzleHttp\ClientInterface $securityClient, string $serverUrl, string $language, string $sdkVersion, string $genVersion)
	{
		$this->_defaultClient = $defaultClient;
		$this->_securityClient = $securityClient;
		$this->_serverUrl = $serverUrl;
		$this->_language = $language;
		$this->_sdkVersion = $sdkVersion;
		$this->_genVersion = $genVersion;
	}
    
    /**
     * sendEmail - Send an email
    */
    public function sendEmail(\ResendLabs\ResendSDK\Models\Operations\SendEmailRequest $request): \ResendLabs\ResendSDK\Models\Operations\SendEmailResponse
    {
        $baseUrl = $this->_serverUrl;
        $url = Utils\Utils::generateURL($baseUrl, '/email');
        
        $options = ['http_errors' => false];
        $body = Utils\Utils::serializeRequestBody($request);
        if ($body === null) {
            throw new \Exception('Request body is required');
        }
        $options = array_merge_recursive($options, $body);
        $options['headers']['user-agent'] = sprintf('speakeasy-sdk/%s %s %s', $this->_language, $this->_sdkVersion, $this->_genVersion);
        
        $httpResponse = $this->_securityClient->request('POST', $url, $options);
        
        $contentType = $httpResponse->getHeader('Content-Type')[0] ?? '';

        $response = new \ResendLabs\ResendSDK\Models\Operations\SendEmailResponse();
        $response->statusCode = $httpResponse->getStatusCode();
        $response->contentType = $contentType;
        
        if ($httpResponse->getStatusCode() === 200) {
            if (Utils\Utils::matchContentType($contentType, 'application/json')) {
                $serializer = Utils\JSON::createSerializer();
                $response->sendEmailResponse = $serializer->deserialize((string)$httpResponse->getBody(), 'ResendLabs\ResendSDK\Models\Shared\SendEmailResponse', 'json');
            }
        }

        return $response;
    }
}