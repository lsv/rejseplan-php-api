[<](../../index.md) Rejseplan - PHP Api - Board data
=========================

### Methods

| Method | Return | Description |
| --- | --- | --- |
| getName() | string | Name of the vehicle |
| getType() | string | Type of the vehicle |
| getStop() | string | Name of the stop |
| getScheduledDate() | \DateTime | Scheduled time |
| getRealDate() | \DateTime | Realtime time |
| isDelayed() | bool | If the vehicle is delayed |
| getScheduledTrack() | string, null | The scheduled track (mostly for trains) |
| getRealTrack() | string, null | Realtime track (mostly for trains) |
| isTrackChanged() | bool, null | Is the track changed - null if the vehicle does not use tracks |
| hasMessages() | bool | Does this vehicle have any messages, can be retrived with journeydetails if needed |
| getFinalStop() | string | The final stop of this vehicle |
| getDirection() | string | The direction of this vehicle |
| getOrigin() | string | The origin of this vehicle |
| getJourneyDetails() | string | A URL to use with [Journey](../../Services/Journey.md) |
