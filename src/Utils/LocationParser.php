<?php

declare(strict_types=1);

namespace Lsv\Rejseplan\Utils;

use Lsv\Rejseplan\Response\CoordinateResponse;
use Lsv\Rejseplan\Response\Location\Stop;

class LocationParser
{
    /**
     * @return array<array-key, string>
     */
    public static function supports(bool $coordinates = true): array
    {
        $supports = ['string', 'int', Stop::class];
        if ($coordinates) {
            $supports[] = CoordinateResponse::class;
        }

        return $supports;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    public static function parse(array &$data, string $key, bool $addKeys = true): void
    {
        if (!isset($data[$key])) {
            return;
        }

        if ($data[$key] instanceof Stop) {
            $newdata = $data[$key];
            $newkey = $key;
            if ($addKeys) {
                $newkey .= 'Id';
            }
            unset($data[$key]);
            $data[$newkey] = $newdata->id;

            return;
        }

        if ($addKeys && $data[$key] instanceof CoordinateResponse) {
            $data[$key.'CoordX'] = self::coordinateToNumber($data[$key]->longitude);
            $data[$key.'CoordY'] = self::coordinateToNumber($data[$key]->latitude);
            unset($data[$key]);

            return;
        }

        if (is_numeric($data[$key])) {
            $newdata = $data[$key];
            $newkey = $key;
            if ($addKeys) {
                $newkey .= 'Id';
            }
            unset($data[$key]);
            $data[$newkey] = $newdata;

            return;
        }

        $data[$key.'CoordName'] = $data[$key];
        unset($data[$key]);
    }

    public static function coordinateToNumber(float $coordinate): string
    {
        return str_replace('.', '', (string) $coordinate);
    }
}
