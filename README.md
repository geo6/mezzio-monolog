# Zend Expressive Monolog ErrorHandler

[![Latest Stable Version](https://poser.pugx.org/geo6/zend-expressive-monolog/v/stable)](https://packagist.org/packages/geo6/zend-expressive-monolog)
[![Total Downloads](https://poser.pugx.org/geo6/zend-expressive-monolog/downloads)](https://packagist.org/packages/geo6/zend-expressive-monolog)
[![Monthly Downloads](https://poser.pugx.org/geo6/zend-expressive-monolog/d/monthly.png)](https://packagist.org/packages/geo6/zend-expressive-monolog)
[![Software License](https://img.shields.io/badge/license-GPL--3.0-brightgreen.svg)](LICENSE)

This library enables [Monolog](https://github.com/Seldaek/monolog) as ErrorHandler in Zend Expressive.

Currently, there are 2 handlers supported (more will be added if needed):

- [`StreamHandler`](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Handler/StreamHandler.php): Logs records into any PHP stream, use this for log files.
- [`SentryHandler`](https://github.com/geo6/zend-expressive-monolog/blob/master/src/Handler/SentryHandler.php): Logs records to [Sentry.io](https://sentry.io/) (requires `sentry/dsk` package).

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
<?php

declare(strict_types=1);

use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
+     Geo6\Expressive\Monolog\ConfigProvider::class,

    // Include cache configuration
    new ArrayProvider($cacheConfig),

    // Default App module config
    App\ConfigProvider::class,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),

    // Load development config if it exists
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
```
