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
 * Add the SAPI name to the log record.
 */
final class SapiNameProcessor extends AbstractThresholdProcessor
{
    protected function process(LogRecord $record): LogRecord
    {
        $record['extra']['sapi'] = \PHP_SAPI;

        return $record;
    }
}
