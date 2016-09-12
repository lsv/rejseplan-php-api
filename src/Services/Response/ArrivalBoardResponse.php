<?php
namespace RejseplanApi\Services\Response;

use RejseplanApi\Services\Response\StationBoard\BoardData;

class ArrivalBoardResponse
{

    /**
     * The list of departures from the location
     *
     * @var BoardData[]
     */
    protected $arrivals;

    /**
     * To get the next departures, use this date
     *
     * @var \DateTime
     */
    protected $nextBoardDate;

    /**
     * @return BoardData[]
     */
    public function getArrivals()
    {
        return $this->arrivals;
    }

    /**
     * @return \DateTime
     */
    public function getNextBoardDate()
    {
        return $this->nextBoardDate;
    }

    /**
     * @param array $data
     * @return ArrivalBoardResponse
     */
    public static function createFromArray(array $data)
    {
        $obj = new self;
        $lastDate = null;
        foreach ($data['Arrival'] as $arrival) {
            $dep = BoardData::createFromArray($arrival);
            $obj->arrivals[] = $dep;
            $lastDate = $dep->getScheduledDate();
        }
        $obj->nextBoardDate = $lastDate;
        return $obj;
    }
}
