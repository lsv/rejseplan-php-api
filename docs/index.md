Rejseplan - PHP Api - Index
=========================

### Base url

The base url is not public, though its still free to get and use.

You just need to write a email to the email listed on this page

http://labs.rejseplanen.dk/api

### Services

| Method | Description |
| --- | --- |
| [ArrivalBoard](Services/ArrivalBoard.md) | To get arrival board for a station |
| [DepartureBoard](Services/DepartureBoard.md) | To get departure board for a station |
| [Journey](Services/Journey.md) | This will get you a full journey report for a vehicle |
| [Location](Services/Location.md) | With this you can find stops, POI or addresses from a user input |
| [NearbyStops](Services/NearbyStops.md) | This will deliver all stops within a radius of a given coordinate.
| [Trip](Services/Trip.md) | With this you can calculate a trip

### Responses

| Method | Description |
| --- | --- |
| [ArrivalBoardResponse](Response/ArrivalBoardResponse.md) | Arrival board response, which comes from a [ArrivalBoard](Services/ArrivalBoard.md) call |
| [DepartureBoardResponse](Response/DepartureBoardResponse.md) | Departure board response, which comes from [DepartureBoard](Services/DepartureBoard.md) call |
| [JourneyResponse](Response/JourneyResponse.md) | Journey details response, which comes from [Journey](Services/Journey.md) call |
| [LocationResponse](Response/LocationResponse.md) | A Location response, which comes from a [Location](Services/Location.md) call |
| [StopLocationResponse](Response/StopLocationResponse.md) | A stop location response, which comes from a [NearbyStops](Services/NearbyStops.md) call |
| [TripResponse](Response/TripResponse.md) | A trip response , which comes from a [Trip](Services/Trip.md) call |

### Coordinate

Some calls requires a `\RejseplanApi\Coordinate` object.
You can create a Coordinate object by calling

```php
$coordinate = new Coordinate( $x, $y );
// Or
$coordinate = new Coordinate();
$coordinate->setXCoordinate( $x );
$coordinate->setYCoordinate( $y );
```
