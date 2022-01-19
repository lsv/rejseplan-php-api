[<](../../index.md) Rejseplan - PHP Api - Nearby stop
============================================================

## `Lsv\Rejseplan\Response\NearbyStop\Stop`

### Example

See [NearbyStopsResponse](../NearbyStopsResponse.md) on how to get `$data`

```php
$data->id;
$data->name;
$data->coordinate;
$data->distance;
```

### Parameters

| Parameter  | Return | Description |
|------------| --- | --- |
| id         | string | ID of the stop |
| name       | string | Name of the stop |
| coordinate | [Coordinate](../CoordinateResponse.md) | Coordinates of this stop |
| distance   | string | Distance to the stop |
