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

/**
 * Add the protocol version to the log record.
 */
final class ProtocolVersionProcessor implements ProcessorInterface, ResettableInterface
{
    private static ?string $protocol = null;

    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['protocol'] = (self::$protocol ??= ($_SERVER['SERVER_PROTOCOL'] ?? 'Unknown'));

        return $record;
    }

    public function reset(): void
    {
        self::$protocol = null;
    }
}
