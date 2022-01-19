[<](../../index.md) Rejseplan - PHP Api - Trip leg
============================================================

## `Lsv\Rejseplan\Response\Trip\Leg`

### Example

See [Trip](../Trip.md) on how to get `$data`

```php
$data->name;
$data->type;
$data->line;
$data->origin;
$data->destination;
$data->notes;
$data->messages;
$data->journeyDetails;
```

### Parameters

| Parameter      | Return | Description |
|----------------| --- | --- |
| name           | string | Name of the line |
| type           | string | Type of the line |
| line           | string | Line name |
| origin         | Address | Origin [address](Address.md) |
| destination    | Address | Destination [address](Address.md) |
| notes          | array<Lsv\Rejseplan\Response\Trip\Note> | [Notes](Note.md) for the leg |
| messages       | array<Lsv\Rejseplan\Response\Trip\Message> | [Messages](Message.md) for the trip |
| journeyDetails | string | Url to the journey details |
