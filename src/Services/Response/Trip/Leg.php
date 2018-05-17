<?php

namespace RejseplanApi\Services\Response\Trip;

use JMS\Serializer\Annotation as Serializer;

class Leg
{
    /**
     * Leg name.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $name;

    /**
     * Leg type.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $type;

    /**
     * Leg origin.
     *
     * @var Place
     * @Serializer\Type("Place")
     */
    protected $origin;

    /**
     * Leg destination.
     *
     * @var Place
     * @Serializer\Type("Place")
     */
    protected $destination;

    /**
     * Notes for this leg.
     *
     * @var array
     * @Serializer\Type("array")
     */
    protected $notes;

    /**
     * Url to leg details.
     *
     * @var string
     * @Serializer\Type("string")
     */
    protected $journyDetails;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Place
     */
    public function getOrigin(): Place
    {
        return $this->origin;
    }

    /**
     * @return Place
     */
    public function getDestination(): Place
    {
        return $this->destination;
    }

    /**
     * @return array
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * @return string
     */
    public function getJournyDetails(): ?string
    {
        return $this->journyDetails;
    }

    /**
     * @param array $data
     *
     * @return Leg
     */
    public static function createFromArray(array $data): Leg
    {
        $obj = new self();
        $obj->name = $data['name'];
        $obj->type = $data['type'];
        $obj->origin = Place::createFromArray($data['Origin']);
        $obj->destination = Place::createFromArray($data['Destination']);

        if (isset($data['Notes']['text'])) {
            $obj->notes = self::setNotes($data['Notes']['text'], $data['type']);
        }

        if (isset($data['JourneyDetailRef']['ref'])) {
            $obj->journyDetails = $data['JourneyDetailRef']['ref'];
        }

        return $obj;
    }

    private static function setNotes($notes, $type)
    {
        $split = '/[;,]/';
        if ($type === 'BIKE') {
            $split = '/[;]/';
        }

        $splitted = preg_split($split, $notes);
        $splitted = array_filter($splitted, function ($value) {
            return $value !== '';
        });

        return $splitted;
    }
}
