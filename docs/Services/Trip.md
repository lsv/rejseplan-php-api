[<](../index.md) Rejseplan - PHP Api - Trip
=========================

With this you can calculate a trip

### Required

| Method | Description |
| --- | --- |
| setOrigin( object ) | The location you want to travel from, should be either<br>a [LocationResponse](../Response/LocationResponse.md)<br>or a [StopLocationResponse](../Response/StopLocationResponse.md) |
| setDestination( object ) | The location you want to travel to, should be either<br>a [LocationResponse](../Response/LocationResponse.md)<br>or a [StopLocationResponse](../Response/StopLocationResponse.md) |

### Optional

| Method | Description |
| --- | --- |
| setVia( object ) | If you want a via point, use this, should be either<br>a [LocationResponse](../Response/LocationResponse.md) object<br>or a [StopLocationResponse](../Response/StopLocationResponse.md) object |
| setDate( \DateTime ) | Set the date of when you want to travel, default now |
| setDontUseBus() | If you dont want to use busses |
| setDontUseTrain() | If you dont want to use trains |
| setDontUseMetro() | If you dont want to use metro |
| setWalkingDistance( int, int ) | Set the walking distance first parameter is walking from origin to station, and last parameter is walking from last station to destination |
| setUseBicycle( int, int ) | If you want to use bicycle (and travel with it), first parameter is cycling from origin to station, and last parameter is cycling from last station to destination |

### Usage

```php
$trip = new \RejseplanApi\Services\Trip($baseurl);
$trip->setOrigin( \RejseplanApi\Response\LocationResponse );
$trip->setDestination( \RejseplanApi\Response\LocationResponse );
$trip->setDontUseMetro();
$response = $trip->call();
```

### Output

The output will be a array of [TripResponse](../Response/TripResponse.md) objects
