<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Models\Shared;



class SendEmailResponse
{
    #[\JMS\Serializer\Annotation\SerializedName('created_at')]
    #[\JMS\Serializer\Annotation\Type("DateTime<'Y-m-d\TH:i:s.up'>")]
    public \DateTime $createdAt;
    
    #[\JMS\Serializer\Annotation\SerializedName('from')]
    #[\JMS\Serializer\Annotation\Type('string')]
    public string $from;
    
    #[\JMS\Serializer\Annotation\SerializedName('id')]
    #[\JMS\Serializer\Annotation\Type('string')]
    public string $id;
    
    #[\JMS\Serializer\Annotation\SerializedName('to')]
    #[\JMS\Serializer\Annotation\Type('string')]
    public string $to;
    
	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->from = "";
		$this->id = "";
		$this->to = "";
	}
}
