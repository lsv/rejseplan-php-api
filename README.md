Rejseplanen - PHP API &ndash; [![Build Status](https://travis-ci.org/lsv/rejseplan-php-api.svg?branch=master)](https://travis-ci.org/lsv/rejseplan-php-api) [![codecov](https://codecov.io/gh/lsv/rejseplan-php-api/branch/master/graph/badge.svg)](https://codecov.io/gh/lsv/rejseplan-php-api) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/babcfce8-7f31-45b4-999f-b78f7ab56960/mini.png)](https://insight.sensiolabs.com/projects/babcfce8-7f31-45b4-999f-b78f7ab56960) [![StyleCI](https://styleci.io/repos/67995566/shield)](https://styleci.io/repos/67995566)

=================

PHP wrapper for Rejseplanen.dk API

### Install

`composer require lsv/rejseplan-php-api`

or add it to your `composer.json` file

```json
"require": {
    "lsv/rejseplan-php-api": "^1.0"
}
```

### Usage

For finding stops nearby a location you can do this

```php
$baseurl = 'https://your-base-url.dk';
$xcoordinate = 55.442952;
$ycoordinate = 11.791372;
$coordinate = new \RejseplanApi\Coordinate($xcoordinate, $ycoordinate);
$nearby = new \RejseplanApi\Services\NearbyStops($baseurl);
$nearby->setCoordinate($coordinate);
$response = $nearby->call();
// Response will now be a array with \RejseplanApi\Response\StopLocationResponse
```

More details in the [documentation](docs/index.md)

### License

The MIT License (MIT)

Copyright (c) 2016 Martin Aarhof martin.aarhof@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
