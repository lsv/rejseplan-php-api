[<](../../index.md) Rejseplan - PHP Api - stop
=========================

### Methods

| Method | Return | Description |
| --- | --- | --- |
| getName() | string | Name of the stop |
| getCoordinate() | [Coordinate](../../index.md#coordinate) | Coordinates of this stop |
| getIndex | int | The route index on the journey detail | 
| getScheduledDeparture | \DateTime, null | Scheduled departure, can be null if its the last leg, then only arrival time will be availible |
| getScheduledArrival | \DateTime, null | Scheduled arrival, can be null if its the first leg, then only departure time is availible |
| getScheduledTrack | string, null | Scheduled track, mostly for trains
| getRealtimeDeparture | \DateTime, null | Realtime departure time
| getRealtimeArrival | \DateTime, null | Realtime arrival time
| getRealtimeTrack | string, null | Realtime track
