<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection;

use Monolog\LogRecord;

class IsHttpsProcessor extends AbstractThresholdProcessor
{
    protected function process(LogRecord $record): LogRecord
    {
        $record['extra']['is_https'] = $this->isHttps();

        return $record;
    }

    private function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? null) === 443;
    }
}
