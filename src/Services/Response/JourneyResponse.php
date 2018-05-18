<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Services\AbstractServiceCall;
use RejseplanApi\Services\Response\Journey\Message;
use RejseplanApi\Services\Response\Journey\Stop;

class JourneyResponse
{
    /**
     * The stops for this journey
     *
     * @var Stop[]
     * @Serializer\Type("array<RejseplanApi\Services\Response\Journey\Stop>")
     */
    protected $stops = [];

    /**
     * The name of this journey
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * The type of journey
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * Notes for the journey
     *
     * @var array
     * @Serializer\Type("array")
     */
    protected $notes = [];

    /**
     * Messages for the journey
     *
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

    public static function createFromArray(array $data): self
    {
        $obj = new self();
        if (isset($data['JourneyName'])) {
            $obj->name = $data['JourneyName']['name'] ?? $data['JourneyName'][0]['name'];
        }

        if (isset($data['JourneyType'])) {
            $obj->type = $data['JourneyType']['type'] ?? $data['JourneyType'][0]['type'];
        }

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

        if (isset($data['Stop'])) {
            $stops = AbstractServiceCall::checkForSingle($data['Stop'], 'name');
            foreach ($stops as $stop) {
                $obj->stops[] = Stop::createFromArray($stop);
            }
        }

        return $obj;
    }
}
