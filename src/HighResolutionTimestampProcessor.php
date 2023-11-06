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
 * Add the high resolution timestamp to the log record.
 */
final class HighResolutionTimestampProcessor extends AbstractThresholdProcessor
{
    protected function process(LogRecord $record): LogRecord
    {
        $record['extra']['hrtime'] = \hrtime(true);

        return $record;
    }
}
