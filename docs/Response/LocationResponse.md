[<](../index.md) Rejseplan - PHP Api - Journey response
=============================================================

## `Lsv\Rejseplan\Response\LocationResponse`

### Example

See [Location](../Location.md) on how to get the `$response`

```php
$response->stops;
$response->pois;
$response->addresses;
```

### Parameters

| Parameter   | Return | Description |
|-------------| --- | --- |
| stops       | array<Lsv\Rejseplan\Response\Location\Stop> | [Stops](Location/Stop.md) for the search |
| pois        | array<Lsv\Rejseplan\Response\Location\Poi> | [Pois](Location/Poi.md) for the search |
| addresses | array<Lsv\Rejseplan\Response\Location\Address> | [Addresses](Location/Address.md) for the search |
