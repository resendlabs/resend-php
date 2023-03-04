<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Models\Operations;



class SendEmailResponse
{
    public string $contentType;
    
    public ?\ResendLabs\ResendSDK\Models\Shared\SendEmailResponse $sendEmailResponse = null;
    
    public int $statusCode;
    
    public ?\Psr\Http\Message\ResponseInterface $rawResponse = null;
    
	public function __construct()
	{
		$this->contentType = "";
		$this->sendEmailResponse = null;
		$this->statusCode = 0;
		$this->rawResponse = null;
	}
}
