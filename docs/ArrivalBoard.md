[<](index.md) Rejseplan - PHP Api - Arrival Board
=========================

## `Lsv\Rejseplan\ArrivalBoard`

To get a arrival board for a stop or station, where stop could be a bus stop or a train station.

### Example

```php
use Lsv\Rejseplan\ArrivalBoard;

$response = (new ArrivalBoard($location))
    ->setDontUseTrain() // Optional
    ->setDontUseBus() // Optional
    ->setDontUseMetro() // Optional
    ->setDate(new DateTime()) // Optional
    ->request();
```

### Arguments

`$location`  can be

* a `\Lsv\Rejseplan\Response\Location\Stop`
* a numeric stop ID

### Optional

| Method | Description |
| --- | --- |
| `setDontUseTrain()` | If you dont want to see trains on the arrival board |
| `setDontUseBus()` | If you dont want to see busses on the arrival board |
| `setDontUseMetro()` | If you dont want to see metro on the arrival board | 
| `setDate(DateTime)` | The date and time of when you want to see the arrival board |

### Response

The output will be a [ArrivalBoardResponse](Response/ArrivalBoardResponse.md) object
