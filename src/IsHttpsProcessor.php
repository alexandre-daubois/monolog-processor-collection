<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Add the high resolution timestamp to the log record.
 */
final class IsHttpsProcessor implements ProcessorInterface
{
    private static ?bool $isHttps = null;

    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['is_https'] = (self::$isHttps ??= $this->isHttps());

        return $record;
    }

    private function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? null) === 443;
    }
}
