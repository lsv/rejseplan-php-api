<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Services\AbstractServiceCall;
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
    public function getStops(): array
    {
        return $this->stops;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * @return Journey\Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public static function createFromArray(array $data): JourneyResponse
    {
        $obj = new self();
        $obj->name = $data['JourneyName']['name'] ?? $data['JourneyName'][0]['name'];
        $obj->type = $data['JourneyType']['type'] ?? $data['JourneyType'][0]['type'];

        if (isset($data['Note'])) {
            if (isset($data['Note'][0]) && \is_array($data['Note'][0])) {
                foreach ($data['Note'] as $note) {
                    $obj->notes[] = $note['text'];
                }
            } else {
                $obj->notes[] = $data['Note']['text'];
            }
        }

        if (isset($data['MessageList']['Message'])) {
            $messages = AbstractServiceCall::checkForSingle($data['MessageList']['Message'], 'Header');
            foreach ($messages as $message) {
                $obj->messages[] = Message::createFromArray($message);
            }
        }

        $stops = AbstractServiceCall::checkForSingle($data['Stop'], 'name');
        foreach ($stops as $stop) {
            $obj->stops[] = Stop::createFromArray($stop);
        }

        return $obj;
    }
}
