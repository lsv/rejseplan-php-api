[<](../../index.md) Rejseplan - PHP Api - Departure board data
============================================================

## `Lsv\Rejseplan\Response\Board\DepartureBoardData`

### Example

See [DepartureBoardResponse](../DepartureBoardResponse.md) on how to get `$data`

```php
$data->getName();
$data->getType();
$data->getStop();
$data->getScheduledDate();
$data->getRealtimeDate();
$data->isDelayed();
$data->getScheduledTrack();
$data->getRealtimeTrack();
$data->isTrackChanged();
$data->hasMessages();
$data->getDirection();
$data->getFinalStop();
$data->getJourney();
```

### Methods

| Parameter | Return | Description |
| --- | --- | --- |
| getName() | string | Name of the vehicle |
| getType() | string | Type of the vehicle |
| getStop() | string | Name of the stop |
| getScheduledDate() | \DateTime | Scheduled time |
| getRealtimeDate() | \DateTime | Realtime time |
| isDelayed() | bool | If the vehicle is delayed |
| getScheduledTrack() | string, null | The scheduled track (mostly for trains) |
| getRealtimeTrack() | string, null | Realtime track (mostly for trains) |
| isTrackChanged() | bool, null | Is the track changed - null if the vehicle does not use tracks |
| hasMessages() | bool | Does this vehicle have any messages, can be retrived with journeydetails if needed |
| getDirection() | string | Direction of the vehicle |
| getFinalStop() | string | Name of the final stop of the vehicle |
| getJourney() | string | A URL to use with [Journey](../../Journey.md) |
