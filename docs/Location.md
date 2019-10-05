[<](index.md) Rejseplan - PHP Api - Location
=========================

## `Lsv\Rejseplan\Location`

With this you can find stops, POI or addresses from a user input

### Example

```php
use Lsv\Rejseplan\Location;

$response = (new Location())
    ->request($searchInput);
```

### Arguments

`$searchInput` can be a string

### Response

The output will be a [LocationResponse](Response/LocationResponse.md) object
