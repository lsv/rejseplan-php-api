[<](../index.md) Rejseplan - PHP Api - Location
=========================

With this you can find stops, POI or addresses from a user input

### Required

| Method | Description |
| --- | --- |
| setInput( string ) | The user input to search for a location 

### Optional

| Method | Description |
| --- | --- |
| setNoStops() | If you dont want to have any stops in your search |
| setNoAddresses() | If you dont want to have any addresses in your search |
| setNoPois() | If you dont want to have any pois in your search | 

### Usage

```php
$board = new \RejseplanApi\Services\Location($baseurl);
$board->setInput($string);
$board->setNoPois();
$response = $board->call();
```

### Output

The output will be a array of [LocationResponse](../Response/LocationResponse.md) objects
