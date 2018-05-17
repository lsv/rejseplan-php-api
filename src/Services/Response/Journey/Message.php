<?php

namespace RejseplanApi\Services\Response\Journey;

use JMS\Serializer\Annotation as Serializer;

class Message
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $header;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $text;

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public static function createFromArray(array $data): Message
    {
        $obj = new self();
        $obj->header = str_replace("\n", '', $data['Header']['$']);
        $obj->text = str_replace(["\n", '  '], ['', "\n"], $data['Text']['$']);

        return $obj;
    }
}
