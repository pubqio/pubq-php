# PUBQ PHP

The PHP library for server-side interact with Pubq channels via HTTP API.

## Installation

You can get the Pubq PHP library via composer:

```bash
$ composer require pubq/pubq-php
```

Or manually add to `composer.json`:

```json
"require": {
    "pubq/pubq-php": "^1.0"
}
```

Then run:

```bash
$ composer update
```

## Quick start

### Construct Pubq HTTP API

Get your application id, key and secret from [Pubq dashboard](https://dashboard.pubq.io) by [creating a new app](https://dashboard.pubq.io/applications/create) or use existing one.

```php
<?php

$pubq = new Pubq\HttpApi(
    "YOUR_APPLICATION_ID",
    "YOUR_APPLICATION_KEY",
    "YOUR_APPLICATION_SECRET",
);
```

### Publishing data to channel

```php
$data['message'] = 'Hello world!';
$pubq->publish('my-channel', $data);
```
