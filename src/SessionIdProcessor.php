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
 * Add the session ID to the log record. If the session is not started, the session ID will be null.
 */
final class SessionIdProcessor implements ProcessorInterface
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $sessionId = \session_id();
        $record['extra']['session_id'] = $sessionId ?: null;

        return $record;
    }
}
