[<](../index.md) Rejseplan - PHP Api - Nearby stops
=========================

This will deliver all stops within a radius of a given coordinate.

### Required

| Method | Description |
| --- | --- |
| setCoordinate( object ) | The [coordinate](../index.md#coordinate) you want to search from

### Optional

| Method | Description |
| --- | --- |
| setRadius( int ) | The radius in meters from the coordinate you will search for stops, The distance is calculated from straight line, and NOT according to roads, default 1000 meters
| setMaxResults( int ) | The max number of results you want, default 30

### Usage

```php
$stops = new \RejseplanApi\Services\NearbyStops($baseurl);
$stops->setCoordinate( \RejseplanApi\Coordinate );
$response = $stops->call();
```

### Output

The output will be a array of [StopLocationResponse](../Response/StopLocationResponse.md) objects
