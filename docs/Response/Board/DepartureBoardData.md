[<](../../index.md) Rejseplan - PHP Api - Departure board data
============================================================

## `Lsv\Rejseplan\Response\Board\DepartureBoardData`

### Example

See [DepartureBoardResponse](../DepartureBoardResponse.md) on how to get `$data`

```php
// List of departures
$departures = $data->departures;
// Single departure
$dep = $departures[0];

$dep->name;
$dep->type;
$dep->stop;
$dep->scheduledDate;
$dep->realtimeDate;
$dep->isDelayed();
$dep->scheduledTrack;
$dep->realtimeTrack;
$dep->isTrackChanged();
$dep->hasMessages;
$dep->direction;
$dep->finalStop;
$dep->journeyDetails;
```

### Parameters

| Parameter      | Return | Description |
|----------------| --- | --- |
| name           | string | Name of the vehicle |
| type           | string | Type of the vehicle |
| stop           | string | Name of the stop |
| scheduledDate  | \DateTime | Scheduled time |
| realtimeDate   | \DateTime | Realtime time |
| scheduledTrack | string, null | The scheduled track (mostly for trains) |
| realtimeTrack  | string, null | Realtime track (mostly for trains) |
| hasMessages    | bool | Does this vehicle have any messages, can be retrived with journeydetails if needed |
| direction      | string | Direction of the vehicle |
| finalStop      | string | Name of the final stop of the vehicle |
| journeyDetails | string | A URL to use with [Journey](../../Journey.md) |

### Methods

| Method              | Return | Description |
|---------------------| --- | --- |
| isDelayed()         | bool | If the vehicle is delayed |
| isTrackChanged()    | bool, null | Is the track changed - null if the vehicle does not use tracks |
