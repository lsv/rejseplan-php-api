[<](../index.md) Rejseplan - PHP Api - Journey response
=============================================================

## `Lsv\Rejseplan\Response\JourneyResponse`

### Example

See [Journey](../Journey.md) on how to get the `$response`

```php
$response->name;
$response->type;
$response->messages;
$response->notes;
$response->stops;
```

### Parameters

| Parameter | Return | Description |
|-----------| --- | --- |
| name      | string | Name of the vehicle |
| type      | string | Type of the vehicle |
| messages  | array<Lsv\Rejseplan\Response\Journey\Message> | Array of [messages](Journey/Message.md) for this journey |
| notes     | array<Lsv\Rejseplan\Response\Journey\Note> | Array of [notes](Journey/Note.md) for this journey |
| stops   | array<Lsv\Rejseplan\Response\Journey\Stop> | Array of [stops](Journey/Stop.md) for this journey |
