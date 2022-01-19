[<](../../index.md) Rejseplan - PHP Api - Journey stop
============================================================

## `Lsv\Rejseplan\Response\Journey\Stop`

### Example

See [JourneyResponse](../JourneyResponse.md) on how to get `$data`

```php
$data->name;
$data->coordinate;
$data->routeIdx;
$data->scheduledDeparture;
$data->realtimeDeparture;
$data->isDepartureDelayed();
$data->scheduledArrival;
$data->realtimeArrival;
$data->isArrivalDelayed();
$data->scheduledTrack;
$data->realtimeTrack;
$data->isTrackChanged();
```

### Parameters

| Parameter            | Return | Description |
|----------------------| --- | --- |
| name                 | string | Name of the stop |
| coordinate           | [Coordinate](../CoordinateResponse.md) | Coordinates of this stop |
| routeIdx             | int | The route index on the journey detail | 
| scheduledDeparture() | \DateTime, null | Scheduled departure, can be null if its the last leg, then only arrival time will be availible |
| realtimeDeparture()  | \DateTime, null | Realtime departure time
| scheduledArrival()   | \DateTime, null | Scheduled arrival, can be null if its the first leg, then only departure time is availible |
| realtimeArrival()    | \DateTime, null | Realtime arrival time
| scheduledTrack()     | string, null | Scheduled track, mostly for trains
| realtimeTrack()      | string, null | Realtime track

### Methods

| Method | Return | Description |
| --- | --- | --- |
| isDepartureDelayed() | boolean | Does the departure time have delays 
| isArrivalDelayed() | boolean | Does the arrival time have delays
| isTrackChanged() | boolean | Has the track changed
