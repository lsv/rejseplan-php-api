[<](index.md) Rejseplan - PHP Api - Departure Board
=========================

## `Lsv\Rejseplan\DepartureBoard`

To get a departure board for a stop or station, where stop could be a bus stop or a train station.

### Example

```php
use Lsv\Rejseplan\DepartureBoard;

$response = (new DepartureBoard())
    ->setDontUseTrain() // Optional
    ->setDontUseBus() // Optional
    ->setDontUseMetro() // Optional
    ->setDate(new DateTime()) // Optional
    ->request($location);
```

### Arguments

`$location`  can be

* a `\Lsv\Rejseplan\Response\Location\Stop`
* a numeric stop ID

### Optional

| Method | Description |
| --- | --- |
| `setDontUseTrain()` | If you dont want to see trains on the departure board |
| `setDontUseBus()` | If you dont want to see busses on the departure board |
| `setDontUseMetro()` | If you dont want to see metro on the departure board | 
| `setDate(DateTime)` | The date and time of when you want to see the departure board |

### Response

The output will be a [DepartureBoardResponse](Response/DepartureBoardResponse.md) object
