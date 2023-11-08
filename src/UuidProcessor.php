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
use Monolog\ResettableInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Generates and add a UUID to the log record for the current request.
 * It uses a timestampable UUID v7.
 */
final class UuidProcessor implements ProcessorInterface, ResettableInterface
{
    private static ?Uuid $uuid = null;

    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['uuid'] = (self::$uuid ??= Uuid::v7())->toRfc4122();

        return $record;
    }

    public function reset(): void
    {
        self::$uuid = null;
    }
}
