# PUBQ PHP SDK

[PUBQ](https://pubq.io) is a pub/sub channels cloud and this is the official PHP client library including only REST interface.

To meet PUBQ and see more info and examples, please read the [documentation](https://pubq.io/docs).

# Getting Started

Follow these steps to just start building with PUBQ in PHP or see the [Quickstart guide](https://pubq.io/docs/getting-started/quickstart) which covers more programming languages.

## Install with package manager

The PHP SDK is available as composer package on Packagist:

```bash
$ composer require pubq/pubq-php
```

Or manually add to `composer.json`:

```json
"require": {
    "pubq/pubq-php": "^1.0.1"
}
```

Then run:

```bash
$ composer update
```

## Interacting with PUBQ

Get your application API key from [PUBQ dashboard](https://dashboard.pubq.io) by [creating a new app](https://dashboard.pubq.io/applications/create) or use existing one.

Construct REST interface of PUBQ PHP SDK:

```php
<?php

$rest = new Pubq\REST(["key" => "YOUR_API_KEY"]);
```

Publish a message with REST interface:

```php
$channel = $rest->channels->get('my-channel');
$channel->publish('Hello!');
```

# Development

Please, read the [contribution guide](https://pubq.io/docs/basics/contribution).

## Setup

```bash
git clone git@github.com:pubqio/pubq-php.git
cd ./pubq-php/
composer install
```

## Tests

To run tests using PHPUnit:

```bash
vendor/bin/phpunit tests
```
