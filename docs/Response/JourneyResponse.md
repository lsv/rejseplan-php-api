[<](../index.md) Rejseplan - PHP Api - Journey response
=============================================================

## `Lsv\Rejseplan\Response\JourneyResponse`

### Example

See [Journey](../Journey.md) on how to get the `$response`

```php
$response->getName();
$response->getType();
$response->getMessages();
$response->getNotes();
$response->getStops();
```

### Methods

| Parameter | Return | Description |
| --- | --- | --- |
| getName() | string | Name of the vehicle |
| getType() | string | Type of the vehicle |
| getMessages() | array<Lsv\Rejseplan\Response\Journey\Message> | Array of [messages](Journey/Message.md) for this journey |
| getNotes() | array<Lsv\Rejseplan\Response\Journey\Note> | Array of [notes](Journey/Note.md) for this journey |
| getStops() | array<Lsv\Rejseplan\Response\Journey\Stop> | Array of [stops](Journey/Stop.md) for this journey |
