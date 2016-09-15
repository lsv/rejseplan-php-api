[<](../index.md) Rejseplan - PHP Api - Journey
=========================

This will deliver information about the complete route of a vehicle.

### Required

| Method | Description |
| --- | --- |
| setUrl( mixed ) | Set the journey, can be either a<br>string with the URL,<br>a [Leg](../Response/Trip/Leg.md) object<br>or a [BoardData](../Response/StationBoard/BoardData.md) object |

One one of these are required

### Usage

```php
$board = new \RejseplanApi\Services\Journey($baseurl);
$board->setUrl($string);
$response = $board->call();
```

### Output

The output will be [JourneyResponse](../Response/JourneyResponse.md) object
