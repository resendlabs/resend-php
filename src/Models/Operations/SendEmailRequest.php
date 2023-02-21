<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Models\Operations;

use \ResendLabs\ResendSDK\Utils\SpeakeasyMetadata;

class SendEmailRequest
{
    #[SpeakeasyMetadata('request:mediaType=application/json')]
    public \ResendLabs\ResendSDK\Models\Shared\Email $request;
    
	public function __construct()
	{
		$this->request = new \ResendLabs\ResendSDK\Models\Shared\Email();
	}
}
