[<](index.md) Rejseplan - PHP Api - Trip
========================================

## `Lsv\Rejseplan\Trip`

With this you can get trips between A and B (and optional via)

### Example

```php
use Lsv\Rejseplan\Trip;

$response = (new Trip($origin, $destination))
    ->setVia($viaLocation) // Optional
    ->setDate(new DateTime()) // Optional
    ->setWalkingDistance($originMaxDistance, $destinationMaxDistance) // Optional
    ->setBicycleDistance($originMaxDistance, $destinationMaxDistance) // Optional
    ->setDontUseTrain() // Optional
    ->setDontUseBus() // Optional
    ->setDontUseMetro() // Optional
    ->request();
```

### Arguments

* `$origin` float, latitude for a coordinate
* `$destination` float, longitude for a coordinate

### Optional

| Method | Description |
| --- | --- |
| setVia($via) | If you want a via point, use this, should be either<br>ID of a stop, a [Location stop](Response/Location/Stop.md) or a [Nearby stop](Response/NearbyStop/Stop.md)
| setDate(DateTime) | Set the date of when you want to travel, default now
| setWalkingDistance(int, int) | Set the walking distance first parameter is walking from origin to station, and last parameter is walking from last station to destination
| setBicycleDistance(int, int) | If you want to use bicycle (and travel with it), first parameter is cycling from origin to station, and last parameter is cycling from last station to destination
| setDontUseTrain() | If you dont want to use trains
| setDontUseBus() | If you dont want to use busses
| setDontUseMetro() | If you dont want to use metro

### Response

The output will be a [TripResponse](Response/TripResponse.md) object
