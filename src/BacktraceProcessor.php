<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection;

use Monolog\LogRecord;

final class BacktraceProcessor extends AbstractThresholdProcessor
{
    protected function process(LogRecord $record): LogRecord
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $stack = [];

        foreach ($trace as $call) {
            if (null === ($file = $call['file'] ?? null)) {
                continue;
            }

            if (\str_contains($file, \DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR)) {
                continue;
            }

            \array_unshift($stack, \sprintf('%s(%d)', $file, $call['line']));
        }

        $record['extra']['backtrace'] = $stack;

        return $record;
    }
}
