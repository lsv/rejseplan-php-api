[<](../index.md) Rejseplan - PHP Api - Departure board
=========================

### Methods

| Method | Return | Description |
| --- | --- | --- |
| getDepartures() | array of [BoardData](StationBoard/BoardData.md) objects | Each departure from this station |
| getNextBoardDate() | \DateTime | Next board date, can be used with [DepartureBoard](../Services/DepartureBoard.md) setDate method |
