[<](../index.md) Rejseplan - PHP Api - Arrival Board
=========================

To get a arrival board for a stop or station, where stop could be a bus stop or a train station.

### Required

| Method | Description |
| --- | --- |
| setLocation( mixed ) | Set the station<br>Input must be either<br>[StopLocationResponse](../Response/StopLocationResponse.md),<br> a [LocationResponse](../Response/LocationResponse.md) (where it must be a stop (can be checked with isStop) otherwise you will get a error),<br>string<br>or a integer. 

### Optional

| Method | Description |
| --- | --- |
| setDontUseTrain() | If you dont want to see trains on the arrival board |
| setDontUseBus() | If you dont want to see busses on the arrival board |
| setDontUseMetro() | If you dont want to see metro on the arrival board | 
| setDate( \DateTime ) | The date of when you want to see the arrival board |

### Usage

```php
$board = new \RejseplanApi\Services\ArrivalBoard($baseurl);
$board->setLocation(LocationResponse);
$response = $board->call();
```

### Output

The output will be a [ArrivalBoardResponse](../Response/ArrivalBoardResponse.md) object
