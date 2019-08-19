<?php

namespace Geo6\Expressive\Monolog\Handler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Sentry\State\Scope;
use Sentry\Severity;
use Throwable;

use function Sentry\withScope;
use function Sentry\captureEvent;

class SentryHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        $payload = [
            'level'   => self::getSeverityFromLevel($record['level']),
            'message' => $record['message'],
        ];

        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof Throwable) {
            $payload['exception'] = $record['context']['exception'];

            unset($record['context']['exception']);
        }

        withScope(function (Scope $scope) use ($record, $payload) {
            // $scope->clear();

            $scope->setExtra('monolog.channel', $record['channel']);
            $scope->setExtra('monolog.level', $record['level_name']);

            $context = $record['context'] ?? [];
            foreach ($context as $key => $value) {
                $scope->setExtra((string) $key, $value);
            }

            $extra = $record['extra'] ?? [];

            foreach ($extra as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        $scope->setExtra(
                            sprintf('%s.%s', (string) $key, (string) $subkey),
                            $subvalue
                        );
                    }
                } else {
                    $scope->setExtra((string) $key, $value);
                }
            }

            captureEvent($payload);
        });
    }

    private static function getSeverityFromLevel(int $level): Severity
    {
        switch ($level) {
            case Logger::DEBUG:
                return Severity::debug();
            case Logger::INFO:
            case Logger::NOTICE:
                return Severity::info();
            case Logger::WARNING:
                return Severity::warning();
            case Logger::ERROR:
                return Severity::error();
            case Logger::CRITICAL:
            case Logger::ALERT:
            case Logger::EMERGENCY:
                return Severity::fatal();
            default:
                return Severity::info();
        }
    }
}
