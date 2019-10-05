[<](index.md) Rejseplan - PHP Api - Journey
=========================

## `Lsv\Rejseplan\Journey`

This will deliver information about the complete route of a vehicle.

### Example

```php
use Lsv\Rejseplan\Journey;

$response = (new Journey())
    ->request($url);
```

### Arguments

`$url`  can be

* a `Lsv\Rejseplan\Response\Board\BoardData`
* a `Lsv\Rejseplan\Response\Trip\Leg`
* a url

### Response

The output will be a [JourneyResponse](Response/JourneyResponse.md) object
