[<](index.md) Rejseplan - PHP Api - Nearby stops
=========================

## `Lsv\Rejseplan\NearbyStops`

This will deliver all stops within a radius of a given coordinate.

### Example

```php
use Lsv\Rejseplan\NearbyStops;

$response = (new NearbyStops())
    ->request($latitude, $longitude);
```

### Arguments

* `$latitude` float, latitude for a coordinate
* `$longitude` float, longitude for a coordinate

### Optional

| Method | Description |
| --- | --- |
| setRadius( int ) | The radius in meters from the coordinate you will search for stops, The distance is calculated from straight line, and NOT according to roads, default 1000 meters
| setMaxResults( int ) | The max number of results you want, default 30

### Response

The output will be a [NearbyStopsResponse](Response/NearbyStopsResponse.md) object
