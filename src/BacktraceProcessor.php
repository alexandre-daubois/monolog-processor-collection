<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection;

use Monolog\LogRecord;

/**
 * Add the backtrace to the log record.
 */
final class BacktraceProcessor extends AbstractThresholdProcessor
{
    private const MONOLOG_VENDOR_DIRNAME = 'vendor/monolog/monolog';

    protected function process(LogRecord $record): LogRecord
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $stack = [];

        foreach ($trace as $call) {
            if (null === ($file = $call['file'] ?? null)) {
                continue;
            }

            $class = $call['class'] ?? null;
            if (\str_contains($file, self::MONOLOG_VENDOR_DIRNAME) || \str_starts_with($class, 'MonologProcessorCollection\\')) {
                continue;
            }

            $stack[] = \sprintf('%s(%d)', $file, $call['line']);
        }

        $record['extra']['backtrace'] = $stack;

        return $record;
    }
}
