<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Models\Shared;

use \ResendLabs\ResendSDK\Utils\SpeakeasyMetadata;

class Security
{
    #[SpeakeasyMetadata('security:scheme=true,type=http,subtype=bearer')]
    public SchemeBearerAuth $bearerAuth;
    
	public function __construct()
	{
		$this->bearerAuth = new \ResendLabs\ResendSDK\Models\Shared\SchemeBearerAuth();
	}
}
