<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\models\operations;



class SendEmailResponse
{
    public string $contentType;
    
    public ?\ResendLabs\ResendSDK\models\shared\SendEmailResponse $sendEmailResponse = null;
    
    public int $statusCode;
    
	public function __construct()
	{
		$this->contentType = "";
		$this->sendEmailResponse = null;
		$this->statusCode = 0;
	}
}
