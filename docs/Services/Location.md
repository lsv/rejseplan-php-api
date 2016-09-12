[<](../index.md) Rejseplan - PHP Api - Location
=========================

With this you can find stops, POI or addresses from a user input

### Required

| Method | Description |
| --- | --- |
| setInput( string ) | The user input to search for a location 

### Usage

```php
$board = new \RejseplanApi\Services\Location($baseurl);
$board->setInput($string);
$response = $board->call();
```

### Output

The output will be a array of [LocationResponse](../Response/LocationResponse.md) objects
