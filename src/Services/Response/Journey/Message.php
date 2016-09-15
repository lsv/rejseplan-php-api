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

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param array $data
     *
     * @return Message
     */
    public static function createFromArray(array $data)
    {
        $obj = new self();
        $obj->header = str_replace("\n", '', $data['Header']['$']);
        $obj->text = str_replace('  ', "\n", str_replace("\n", '', $data['Text']['$']));

        return $obj;
    }
}
