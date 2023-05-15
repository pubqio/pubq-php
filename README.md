# PUBQ PHP

The PHP library for server-side interact with Pubq channels via HTTP API.

## Installation

You can get the Pubq PHP library via composer:

```bash
$ composer require pubq/pubq-php
```

Or add to `composer.json`:

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

```php
<?php

$pubq = new Pubq\HttpApi(
    "YOUR_APPLICATION_KEY",
    "YOUR_APPLICATION_SECRET",
);
```

### Publishing data to channel

```php
$data['message'] = 'Hello world!';
$pubq->publish('my-channel', $data);
```