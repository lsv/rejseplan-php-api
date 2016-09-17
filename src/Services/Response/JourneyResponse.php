<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Services\Response\Journey\Message;
use RejseplanApi\Services\Response\Journey\Stop;

class JourneyResponse
{
    /**
     * @var Stop[]
     * @Serializer\Type("array<RejseplanApi\Services\Response\Journey\Stop>")
     */
    protected $stops = [];

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    protected $notes = [];

    /**
     * @var Message[]
     * @Serializer\Type("array<RejseplanApi\Services\Response\Journey\Message>")
     */
    protected $messages = [];

    /**
     * @return Stop[]
     */
    public function getStops()
    {
        return $this->stops;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @return Journey\Message[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $data
     *
     * @return JourneyResponse
     */
    public static function createFromArray(array $data)
    {
        $obj = new self();
        $obj->name = $data['JourneyName']['name'];
        $obj->type = $data['JourneyType']['type'];

        if (isset($data['Note'])) {
            foreach ($data['Note'] as $note) {
                $obj->notes[] = $note['text'];
            }
        }

        if (isset($data['MessageList'])) {
            foreach ($data['MessageList']['Message'] as $message) {
                $obj->messages[] = Message::createFromArray($message);
            }
        }

        foreach ($data['Stop'] as $stop) {
            $obj->stops[] = Stop::createFromArray($stop);
        }

        return $obj;
    }
}
