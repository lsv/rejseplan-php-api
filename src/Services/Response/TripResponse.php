<?php

namespace RejseplanApi\Services\Response;

use JMS\Serializer\Annotation as Serializer;
use RejseplanApi\Services\Response\Trip\Leg;

class TripResponse
{
    /**
     * Legs for this trip.
     *
     * @var Leg[]
     * @Serializer\Type("array<RejseplanApi\Services\Response\Trip\Leg>")
     */
    protected $legs;

    /**
     * Time when departure.
     *
     * @var \DateTime
     * @Serializer\Type("\DateTime")
     */
    protected $departureDate;

    /**
     * Time on arrival.
     *
     * @var \DateTime
     * @Serializer\Type("\DateTime")
     */
    protected $arrivalDate;

    /**
     * @return Trip\Leg[]
     */
    public function getLegs()
    {
        return $this->legs;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * @param array $data
     *
     * @return TripResponse
     */
    public static function createFromArray(array $data)
    {
        $obj = new self();
        $legs = [];
        $firstLegTime = null;
        $lastLegTime = null;
        $legNumber = 0;
        foreach ($data['Leg'] as $leg) {
            $legData = Leg::createFromArray($leg);
            $legs[] = $legData;

            if (++$legNumber === 1) {
                $firstLegTime = $legData->getOrigin()->getDate();
            }

            $lastLegTime = $legData->getDestination()->getDate();
        }

        $obj->legs = $legs;
        $obj->departureDate = $firstLegTime;
        $obj->arrivalDate = $lastLegTime;

        return $obj;
    }
}
