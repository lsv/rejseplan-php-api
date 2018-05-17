<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Services\AbstractServiceCall;
use RejseplanApi\Services\Response\StationBoard\BoardData;

class DepartureBoardResponse
{
    /**
     * The list of departures from the location.
     *
     * @var BoardData[]
     *
     * @Serializer\Type("array<RejseplanApi\Services\Response\StationBoard\BoardData>")
     */
    protected $departures = [];

    /**
     * To get the next departures, use this date.
     *
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime")
     */
    protected $nextBoardDate;

    /**
     * @return BoardData[]
     */
    public function getDepartures(): array
    {
        return $this->departures;
    }

    public function getNextBoardDate(): ?\DateTime
    {
        return $this->nextBoardDate;
    }

    public static function createFromArray(array $data): DepartureBoardResponse
    {
        $obj = new self();
        $lastDate = null;

        if (!isset($data['Departure'])) {
            return $obj;
        }

        $departures = AbstractServiceCall::checkForSingle($data['Departure'], 'name');
        foreach ($departures as $departure) {
            $dep = BoardData::createFromArray($departure);
            $obj->departures[] = $dep;

            $lastDate = $dep->getScheduledDate();
            if ($dep->isDelayed()) {
                $lastDate = $dep->getRealDate();
            }
        }
        $obj->nextBoardDate = $lastDate;

        return $obj;
    }
}
