[<](../../index.md) Rejseplan - PHP Api - Nearby stop
============================================================

## `Lsv\Rejseplan\Response\NearbyStop\Stop`

### Example

See [NearbyStopsResponse](../NearbyStopsResponse.md) on how to get `$data`

```php
$data->getId();
$data->getName();
$data->getCoordinate();
$data->getDistance();
```

### Methods

| Parameter | Return | Description |
| --- | --- | --- |
| getId() | string | ID of the stop |
| getName() | string | Name of the stop |
| getCoordinate() | [Coordinate](../CoordinateResponse.md) | Coordinates of this stop |
| getDistance() | string | Distance to the stop |
