[<](../index.md) Rejseplan - PHP Api - Departure board response
=============================================================

## `Lsv\Rejseplan\Response\DepartureBoardResponse`

### Example

See [DepartureBoard](../DepartureBoard.md) on how to get the `$response`

```php
foreach ($response->getDepartures() as $departure) {
    // $departure is now a `Lsv\Rejseplan\Response\Board\DepartureBoardData`
}
```

[Lsv\Rejseplan\Response\Board\DepartureBoardData](Board/DepartureBoardData.md)
