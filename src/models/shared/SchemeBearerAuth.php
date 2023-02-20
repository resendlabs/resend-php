<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\models\shared;

use \ResendLabs\ResendSDK\utils\SpeakeasyMetadata;

class SchemeBearerAuth
{
    #[SpeakeasyMetadata('security:name=Authorization')]
    public string $authorization;
    
	public function __construct()
	{
		$this->authorization = "";
	}
}
