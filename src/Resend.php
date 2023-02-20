<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK;

class Resend
{
	public const SERVERS = [
		'https://api.resend.com',
	];
  	
  	public Emails $emails;	

	private ?\GuzzleHttp\ClientInterface $_defaultClient;
	private ?\GuzzleHttp\ClientInterface $_securityClient;
	private ?models\shared\Security $_security;
	private string $_serverUrl;
	private string $_language = "php";
	private string $_sdkVersion = "1.1.1";
	private string $_genVersion = "1.3.3";

	public static function builder(): ResendBuilder
	{
		return new ResendBuilder();
	}

	/**
	 * @param \GuzzleHttp\ClientInterface|null $client	 
	 * @param models\shared\Security|null $security
	 * @param string $serverUrl
	 * @param array<string, string>|null $params
	 */
	public function __construct(?\GuzzleHttp\ClientInterface $client, ?models\shared\Security $security, string $serverUrl, ?array $params)
	{
		$this->_defaultClient = $client;
		
		if (is_null($this->_defaultClient)) {
			$this->_defaultClient = new \GuzzleHttp\Client([
            	'timeout' => 60,
			]);
		}

		$this->_securityClient = null;
		if (!is_null($security)) {
			$this->_security = $security;
			$this->_securityClient = utils\Utils::configureSecurityClient($this->_defaultClient, $this->_security);
		}
		
		if (is_null($this->_securityClient)) {
			$this->_securityClient = $this->_defaultClient;
		}

		if (!empty($serverUrl)) {
			$this->_serverUrl = utils\Utils::replaceParameters($serverUrl, $params);
		}
		
		if (empty($this->_serverUrl)) {
			$this->_serverUrl = $this::SERVERS[0];
		}
		
		$this->emails = new Emails(
			$this->_defaultClient,
			$this->_securityClient,
			$this->_serverUrl,
			$this->_language,
			$this->_sdkVersion,
			$this->_genVersion
		);
	}
}