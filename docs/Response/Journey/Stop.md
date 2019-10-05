[<](../../index.md) Rejseplan - PHP Api - Journey stop
============================================================

## `Lsv\Rejseplan\Response\Journey\Stop`

### Example

See [JourneyResponse](../JourneyResponse.md) on how to get `$data`

```php
$data->getName();
$data->getCoordinate();
$data->getIndex();
$data->getScheduledDeparture();
$data->getRealtimeDeparture();
$data->isDepartureDelayed();
$data->getScheduledArrival();
$data->getRealtimeArrival();
$data->isArrivalDelayed();
$data->getScheduledTrack();
$data->getRealtimeTrack();
$data->isTrackChanged();
```

### Methods

| Method | Return | Description |
| --- | --- | --- |
| getName() | string | Name of the stop |
| getCoordinate() | [Coordinate](../CoordinateResponse.md) | Coordinates of this stop |
| getIndex() | int | The route index on the journey detail | 
| getScheduledDeparture() | \DateTime, null | Scheduled departure, can be null if its the last leg, then only arrival time will be availible |
| getRealtimeDeparture() | \DateTime, null | Realtime departure time
| isDepartureDelayed() | boolean | Does the departure time have delays 
| getScheduledArrival() | \DateTime, null | Scheduled arrival, can be null if its the first leg, then only departure time is availible |
| getRealtimeArrival() | \DateTime, null | Realtime arrival time
| isArrivalDelayed() | boolean | Does the arrival time have delays
| getScheduledTrack() | string, null | Scheduled track, mostly for trains
| getRealtimeTrack() | string, null | Realtime track
| isTrackChanged() | boolean | Has the track changed
