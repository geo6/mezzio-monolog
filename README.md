# Mezzio Monolog ErrorHandler

[![Latest Stable Version](https://poser.pugx.org/geo6/mezzio-monolog/v/stable)](https://packagist.org/packages/geo6/mezzio-monolog)
[![Total Downloads](https://poser.pugx.org/geo6/mezzio-monolog/downloads)](https://packagist.org/packages/geo6/mezzio-monolog)
[![Monthly Downloads](https://poser.pugx.org/geo6/mezzio-monolog/d/monthly.png)](https://packagist.org/packages/geo6/mezzio-monolog)
[![Software License](https://img.shields.io/badge/license-GPL--3.0-brightgreen.svg)](LICENSE)

This library enables [Monolog](https://github.com/Seldaek/monolog) as ErrorHandler in Mezzio.

Currently, there are 2 handlers supported (more will be added if needed):

- [`StreamHandler`](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Handler/StreamHandler.php): Logs records into any PHP stream, use this for log files.
- [`SentryHandler`](https://github.com/geo6/mezzio-monolog/blob/master/src/Handler/SentryHandler.php): Logs records to [Sentry.io](https://sentry.io/) (requires `sentry/sdk` package).

## Install

```cmd
composer require geo6/mezzio-monolog
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

To enable it, you just have to add `Geo6\Mezzio\Monolog\ConfigProvider::class` to your main configuration (usually `config/config.php`):

```diff
...

$aggregator = new ConfigAggregator([
+     Geo6\Mezzio\Monolog\ConfigProvider::class,

    ...
], $cacheConfig['config_cache_path']);

...
```

The Monolog ErrorHandler will be active only in "production mode" (when `$config['debug]` is `false`).  
To switch to "production mode", you can use `composer run development-disable`.
