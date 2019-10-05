<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Traits;

trait Coordinate
{
    protected function makeCoordinate(array &$items): array
    {
        foreach ($items as &$item) {
            $item['coordinate'] = [
                'longitude' => $this->numberToCoord($item['x']),
                'latitude' => $this->numberToCoord($item['y']),
            ];
        }

        return $items;
    }

    private function numberToCoord($coord): float
    {
        return (float) sprintf('%s.%s', substr((string) $coord, 0, 2), substr((string) $coord, 2));
    }
}
