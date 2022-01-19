[<](index.md) Rejseplan - PHP Api - Client
=========================

To set the HTTP client.

If you haven't already added a psr18 http client, you should add it with

```bash
# Add a PSR-18 client, fx
composer require symfony/http-client
# If you add another PSR18 client, then look below on how to use other PSR 18 clients
```

Then add before your requests with

```php
$client = new \Symfony\Component\HttpClient\HttpClient(); // Any PSR-18 http client can be used
\Lsv\Rejseplan\AbstractRequest::setClient($client);
```
