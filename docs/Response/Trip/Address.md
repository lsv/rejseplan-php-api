[<](../../index.md) Rejseplan - PHP Api - Trip address
============================================================

## `Lsv\Rejseplan\Response\Trip\Address`

### Example

See [Leg](Leg.md) on how to get `$data`

```php
$data->getName();
$data->getType();
$data->getRouteIdx();
$data->getScheduledDate();
$data->getRealtimeDate();
$data->getScheduledTrack();
$data->getRealtimeTrack();
$data->isDelayed();
$data->isTrackChanged();
```

### Methods

| Parameter | Return | Description |
| --- | --- | --- |
| getName() | string | Name of the line |
| getType() | string | Type of the line |
| getRouteIdx() | string | Route index |
| getScheduledDate() | DateTime | Scheduled date |
| getRealtimeDate() | DateTime | Realtime date |
| getScheduledTrack() | null\|int | Scheduled track |
| getRealtimeTrack() | null\|int | Realtime track |
| isDelayed() | bool | Is the trip delayed on this address |
| isTrackChanged() | bool | Is the track changed on this address |
