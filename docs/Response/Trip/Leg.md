[<](../../index.md) Rejseplan - PHP Api - Trip leg
============================================================

## `Lsv\Rejseplan\Response\Trip\Leg`

### Example

See [Trip](../Trip.md) on how to get `$data`

```php
$data->getName();
$data->getType();
$data->getLine();
$data->getOrigin();
$data->getDestination();
$data->getNotes();
$data->getMessages();
$data->getJourney();
```

### Methods

| Parameter | Return | Description |
| --- | --- | --- |
| getName() | string | Name of the line |
| getType() | string | Type of the line |
| getLine() | string | Line name |
| getOrigin() | Address | Origin [address](Address.md) |
| getDestination() | Address | Destination [address](Address.md) |
| getNotes() | array<Lsv\Rejseplan\Response\Trip\Note> | [Notes](Note.md) for the leg |
| getMessages() | array<Lsv\Rejseplan\Response\Trip\Message> | [Messages](Message.md) for the trip |
| getJourney() | string | Url to the journey details |
