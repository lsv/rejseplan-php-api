[<](../../index.md) Rejseplan - PHP Api - Trip address
============================================================

## `Lsv\Rejseplan\Response\Trip\Address`

### Example

See [Leg](Leg.md) on how to get `$data`

```php
$data->name;
$data->type;
$data->routeIdx;
$data->scheduledDate;
$data->realtimeDate;
$data->isDelayed();
$data->scheduledTrack;
$data->realtimeTrack;
$data->isTrackChanged();
```

### Parameters

| Parameter       | Return | Description |
|-----------------| --- | --- |
| name            | string | Name of the line |
| type            | string | Type of the line |
| routeIdx        | string | Route index |
| scheduledDate   | DateTime | Scheduled date |
| realtimeDate    | DateTime | Realtime date |
| scheduledTrack  | null\|int | Scheduled track |
| realtimeTrack | null\|int | Realtime track |

### Methods

| Method              | Return | Description |
|---------------------| --- | --- |
| isDelayed()         | bool | Is the trip delayed on this address |
| isTrackChanged()    | bool | Is the track changed on this address |
