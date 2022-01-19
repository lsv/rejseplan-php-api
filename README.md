Rejseplanen - PHP API
---------------------

PHP wrapper for Rejseplanen.dk API

### Install

```bash
composer require lsv/rejseplan-php-api

# Add a PSR-18 client, fx
composer require symfony/http-client
# If you add another PSR18 client, then look below on how to use other PSR 18 clients
```


### Usage

```php
$client = new \Symfony\Component\HttpClient\HttpClient(); // Any PSR-18 http client can be used
\Lsv\Rejseplan\AbstractRequest::setClient($client);

$location = '123'; // Location should be either a string, integer or a StopLocation
$board = new \Lsv\Rejseplan\ArrivalBoard($location);
$response = $board->request();
// $response is now a ArrivalBoardResponse
```

See [ArrivalBoard](docs/ArrivalBoard.md) for more

### More usages

| Request                             | Description                                                        | 
|-------------------------------------|--------------------------------------------------------------------|
| [Client](docs/Client.md)            | Set HTTP client                                                    |
| [ArrivalBoard](docs/ArrivalBoard.md)     | To get arrival board for a station                                 |
| [DepartureBoard](docs/DepartureBoard.md) | To get departure board for a station                               |
| [Journey](docs/Journey.md)               | This will get you a full journey report for a vehicle              |
| [Location](docs/Location.md)             | With this you can find stops, POI or addresses from a user input   |
| [NearbyStops](docs/NearbyStops.md)       | This will deliver all stops within a radius of a given coordinate. |
| [Trip](docs/Trip.md)                     | With this you can calculate a trip                                 |

### License

The MIT License (MIT)

Copyright (c) 2019 Martin Aarhof martin.aarhof@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
