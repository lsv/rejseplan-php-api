[<](../index.md) Rejseplan - PHP Api - location
=========================

### Methods

| Method | Return | Description |
| --- | --- | --- |
| getId() | string, null | The ID of this location - If null then its either a address or a POI |
| getName() | string | The name of this location |
| getCoordinate() | [Coordinate](../index.md#coordinate) | The coordinates for this location
| isStop() | bool | If this location is a stop |
| isAddress() | bool | If this location is a address |
| isPOI() | bool | If this location is a POI |
