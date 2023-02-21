<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Models\Shared;

use \ResendLabs\ResendSDK\Utils\SpeakeasyMetadata;

class SchemeBearerAuth
{
    #[SpeakeasyMetadata('security:name=Authorization')]
    public string $authorization;
    
	public function __construct()
	{
		$this->authorization = "";
	}
}
