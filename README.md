# Zend Expressive Monolog ErrorHandler

[![Latest Stable Version](https://poser.pugx.org/geo6/zend-expressive-monolog/v/stable)](https://packagist.org/packages/geo6/zend-expressive-monolog)
[![Total Downloads](https://poser.pugx.org/geo6/zend-expressive-monolog/downloads)](https://packagist.org/packages/geo6/zend-expressive-monolog)
[![Monthly Downloads](https://poser.pugx.org/geo6/zend-expressive-monolog/d/monthly.png)](https://packagist.org/packages/geo6/zend-expressive-monolog)
[![Software License](https://img.shields.io/badge/license-GPL--3.0-brightgreen.svg)](LICENSE)

This library enables [Monolog](https://github.com/Seldaek/monolog) as ErrorHandler in Zend Expressive.

Currently, there are 2 handlers supported (more will be added if needed):

- [`StreamHandler`](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Handler/StreamHandler.php): Logs records into any PHP stream, use this for log files.
- [`SentryHandler`](https://github.com/geo6/zend-expressive-monolog/blob/master/src/Handler/SentryHandler.php): Logs records to [Sentry.io](https://sentry.io/) (requires `sentry/sdk` package).

## Install

```
composer require geo6/zend-expressive-monolog
```

## Configuration

Create a `monolog.global.php` file in your `config` directory:

```php
<?php

declare(strict_types=1);

return [
    // StreamHandler
    'stream' => [
        'path' => 'data/log/myapp.log',
    ],
    // SentryHanlder
    'sentry' => [
        'dsn' => 'https://xxxxx@sentry.io/12345',
    ],
];
```

## Usage

To enable it, you just have to add `Geo6\Expressive\Monolog\ConfigProvider::class` to your main configuration (usually `config/config.php`):

```diff
...

$aggregator = new ConfigAggregator([
+     Geo6\Expressive\Monolog\ConfigProvider::class,

    ...
], $cacheConfig['config_cache_path']);

...
```

The Monolog ErrorHandler will be active only in "production mode" (when `$config['debug]` is `false`).  
To switch to "production mode", you can use `composer run development-disable`.
