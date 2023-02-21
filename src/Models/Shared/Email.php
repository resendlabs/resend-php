<?php

declare(strict_types=1);

namespace ResendLabs\ResendSDK\Models\Shared;



class Email
{
    #[\JMS\Serializer\Annotation\SerializedName('bcc')]
    #[\JMS\Serializer\Annotation\Type('string')]
    #[\JMS\Serializer\Annotation\SkipWhenEmpty]
    public ?string $bcc = null;
    
    #[\JMS\Serializer\Annotation\SerializedName('cc')]
    #[\JMS\Serializer\Annotation\Type('string')]
    #[\JMS\Serializer\Annotation\SkipWhenEmpty]
    public ?string $cc = null;
    
    #[\JMS\Serializer\Annotation\SerializedName('from')]
    #[\JMS\Serializer\Annotation\Type('string')]
    public string $from;
    
    #[\JMS\Serializer\Annotation\SerializedName('html')]
    #[\JMS\Serializer\Annotation\Type('string')]
    #[\JMS\Serializer\Annotation\SkipWhenEmpty]
    public ?string $html = null;
    
    #[\JMS\Serializer\Annotation\SerializedName('reply_to')]
    #[\JMS\Serializer\Annotation\Type('string')]
    #[\JMS\Serializer\Annotation\SkipWhenEmpty]
    public ?string $replyTo = null;
    
    #[\JMS\Serializer\Annotation\SerializedName('subject')]
    #[\JMS\Serializer\Annotation\Type('string')]
    public string $subject;
    
    #[\JMS\Serializer\Annotation\SerializedName('text')]
    #[\JMS\Serializer\Annotation\Type('string')]
    #[\JMS\Serializer\Annotation\SkipWhenEmpty]
    public ?string $text = null;
    
    #[\JMS\Serializer\Annotation\SerializedName('to')]
    #[\JMS\Serializer\Annotation\Type('string')]
    public string $to;
    
	public function __construct()
	{
		$this->bcc = null;
		$this->cc = null;
		$this->from = "";
		$this->html = null;
		$this->replyTo = null;
		$this->subject = "";
		$this->text = null;
		$this->to = "";
	}
}
