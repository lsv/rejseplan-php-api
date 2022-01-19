[<](../../index.md) Rejseplan - PHP Api - Arrival board data
============================================================

## `Lsv\Rejseplan\Response\Board\ArrivalBoardData`

### Example

See [ArrivalBoardResponse](../ArrivalBoardResponse.md) on how to get `$data`

```php
// List of arrivals
$arrivals = $data->arrivals;
// Single arrival
$arr = $arrivals[0];

$arr->name;
$arr->type;
$arr->stop;
$arr->scheduledDate;
$arr->realtimeDate;
$arr->isDelayed();
$arr->scheduledTrack;
$arr->realtimeTrack;
$arr->isTrackChanged();
$arr->hasMessages;
$arr->origin;
$arr->journeyDetails;
```

### Parameters

| Parameter        | Return | Description |
|------------------| --- | --- |
| name             | string | Name of the vehicle |
| type             | string | Type of the vehicle |
| stop             | string | Name of the stop |
| scheduledDate  | \DateTime | Scheduled time |
| realtimeDate   | \DateTime | Realtime time |
| scheduledTrack | string, null | The scheduled track (mostly for trains) |
| realtimeTrack  | string, null | Realtime track (mostly for trains) |
| hasMessages      | bool | Does this vehicle have any messages, can be retrived with journeydetails if needed |
| origin           | string | The origin of this vehicle |
| journeyDetails | string | A URL to use with [Journey](../../Journey.md) |


### Methods

| Method              | Return | Description |
|---------------------| --- | --- |
| isDelayed()         | bool | If the vehicle is delayed |
| isTrackChanged()    | bool, null | Is the track changed - null if the vehicle does not use tracks |
