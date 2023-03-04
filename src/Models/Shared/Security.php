<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Models\Shared;

use \ResendLabs\ResendSDK\Utils\SpeakeasyMetadata;

class Security
{
    #[SpeakeasyMetadata('security:scheme=true,type=http,subtype=bearer,name=Authorization')]
    public string $bearerAuth;
    
	public function __construct()
	{
		$this->bearerAuth = "";
	}
}
