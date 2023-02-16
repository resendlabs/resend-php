<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\models\operations;

use \ResendLabs\ResendSDK\utils\SpeakeasyMetadata;

class SendEmailRequest
{
    #[SpeakeasyMetadata('request:mediaType=application/json')]
    public \ResendLabs\ResendSDK\models\shared\Email $request;
    
    public ?\ResendLabs\ResendSDK\models\utils\RetryConfig $retries = null;
    
	public function __construct()
	{
		$this->request = new \ResendLabs\ResendSDK\models\shared\Email();
		$this->retries = null;
	}
}
