<?php

namespace Geo6\Mezzio\Monolog\Listener;

use Geo6\Mezzio\Monolog\Handler\SentryHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function Sentry\init;
use Throwable;

class Listener
{
    /** @var array */
    private $config;

    /** @var bool */
    private $debug;

    /** @var \Monolog\Logger */
    private $monolog;

    public function __construct(array $config, bool $debug)
    {
        $this->config = $config;
        $this->debug = $debug;

        $this->monolog = new Logger('mapper');

        $this->monolog->pushProcessor(new GitProcessor());

        if (isset($config['stream'])) {
            $path = $config['stream']['path'];
            $level = $config['stream']['level'] ?? Logger::DEBUG;

            $this->monolog->pushHandler(new StreamHandler($path, $level));
        }

        if (isset($config['sentry'])) {
            $level = $config['sentry']['level'] ?? Logger::DEBUG;

            init($config['sentry']);

            $this->monolog->pushHandler(new SentryHandler($level));
        }
    }

    public function __invoke(Throwable $error, ServerRequestInterface $request, ResponseInterface $response): void
    {
        $context = [
            'file'      => $error->getFile(),
            'line'      => $error->getLine(),
            'code'      => $error->getCode(),
            'exception' => $error,
        ];

        if ($error instanceof \Error) {
            $this->monolog->critical($error->getMessage(), $context);
        } elseif ($error instanceof \ErrorException) {
            $this->monolog->error($error->getMessage(), $context);
        } else {
            $this->monolog->warning($error->getMessage(), $context);
        }
    }

    public function isEnabled(): bool
    {
        return $this->debug === false;
    }

    /**
     * Adds a log record at an arbitrary level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param string|int $level   The log level
     * @param string     $message The log message
     * @param array      $context The log context
     */
    public function log($level, string $message, array $context = []): void
    {
        $this->monolog->log($level, $message, $context);
    }

    /**
     * Adds a log record at the DEBUG level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function debug(string $message, array $context = []): void
    {
        $this->monolog->debug($message, $context);
    }

    /**
     * Adds a log record at the INFO level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function info(string $message, array $context = []): void
    {
        $this->monolog->info($message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function notice(string $message, array $context = []): void
    {
        $this->monolog->notice($message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->monolog->warning($message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function error(string $message, array $context = []): void
    {
        $this->monolog->error($message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function critical(string $message, array $context = []): void
    {
        $this->monolog->critical($message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function alert(string $message, array $context = []): void
    {
        $this->monolog->alert($message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->monolog->emergency($message, $context);
    }
}
