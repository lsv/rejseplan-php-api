[<](../index.md) Rejseplan - PHP Api - Journey
=========================

This will deliver information about the complete route of a vehicle.

### Required

| Method | Description |
| --- | --- |
| setUrl( mixed ) | Set the journey, can either be a string with the URL, a [Leg](../Response/Trip/Leg.md) object or a [BoardData](../Response/StationBoard/BoardData.md) object |

One one of these are required

### Usage

```php
$board = new \RejseplanApi\Services\Journey($baseurl);
$board->setUrl($string);
$response = $board->call();
```

### Output

The output will be [JourneyResponse](../Response/JourneyResponse.md) object
