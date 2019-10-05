[<](../index.md) Rejseplan - PHP Api - Journey response
=============================================================

## `Lsv\Rejseplan\Response\LocationResponse`

### Example

See [Location](../Location.md) on how to get the `$response`

```php
$response->getStops();
$response->getPois();
$response->getAddresses();
```

### Methods

| Parameter | Return | Description |
| --- | --- | --- |
| getStops() | array<Lsv\Rejseplan\Response\Location\Stop> | [Stops](Location/Stop.md) for the search |
| getPois() | array<Lsv\Rejseplan\Response\Location\Poi> | [Pois](Location/Poi.md) for the search |
| getAddresses() | array<Lsv\Rejseplan\Response\Location\Address> | [Addresses](Location/Address.md) for the search |
