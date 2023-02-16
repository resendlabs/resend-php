<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\models\shared;

use \ResendLabs\ResendSDK\utils\SpeakeasyMetadata;

class Security
{
    #[SpeakeasyMetadata('security:scheme=true,type=http,subtype=bearer')]
    public SchemeBearerAuth $bearerAuth;
    
	public function __construct()
	{
		$this->bearerAuth = new \ResendLabs\ResendSDK\models\shared\SchemeBearerAuth();
	}
}
