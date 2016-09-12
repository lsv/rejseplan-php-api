[<](../index.md) Rejseplan - PHP Api - Departure Board
=========================

To get a departure board for a stop or station, where stop could be a bus stop or a train station.

### Required

| Method | Description |
| --- | --- |
| setLocation( object ) | Set the station - The object must be a [StopLocationResponse](../Response/StopLocationResponse.md) or a [LocationResponse](../Response/LocationResponse.md) where it must be a stop (can be checked with isStop) otherwise you will get a error

Only one is required

### Optional

| Method | Description |
| --- | --- |
| setDontUseTrain() | If you dont want to see trains on the arrival board |
| setDontUseBus() | If you dont want to see busses on the arrival board |
| setDontUseMetro() | If you dont want to see metro on the arrival board | 
| setDate( \DateTime ) | The date of when you want to see the arrival board |

### Usage

```php
$board = new \RejseplanApi\Services\DepartureBoard($baseurl);
$board->setLocation(LocationResponse);
$response = $board->call();
```

### Output

The output will be a [DepartureBoardResponse](../Response/DepartureBoardResponse.md) object
