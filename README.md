Rejseplanen - PHP API
---------------------

[![Build Status](https://travis-ci.org/lsv/rejseplan-php-api.svg?branch=master)](https://travis-ci.org/lsv/rejseplan-php-api) 
[![codecov](https://codecov.io/gh/lsv/rejseplan-php-api/branch/master/graph/badge.svg)](https://codecov.io/gh/lsv/rejseplan-php-api)

PHP wrapper for Rejseplanen.dk API

### Install

`composer require lsv/rejseplan-php-api`

### Usages

| Request | Description | 
| --- | --- |
| [ArrivalBoard](ArrivalBoard.md) | To get arrival board for a station |
| [DepartureBoard](DepartureBoard.md) | To get departure board for a station |
| [Journey](Journey.md) | This will get you a full journey report for a vehicle |
| [Location](Location.md) | With this you can find stops, POI or addresses from a user input |
| [NearbyStops](NearbyStops.md) | This will deliver all stops within a radius of a given coordinate. |
| [Trip](Trip.md) | With this you can calculate a trip |

### License

The MIT License (MIT)

Copyright (c) 2019 Martin Aarhof martin.aarhof@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
