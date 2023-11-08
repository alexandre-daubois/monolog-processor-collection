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
 * Add the client IP to the log record. If the client IP is not available, the client IP will be null.
 * Make sure you have the right to store client IP addresses where your logs are stored.
 */
final class ClientIpProcessor implements ProcessorInterface, ResettableInterface
{
    private static ?string $ip = null;

    public function __invoke(LogRecord $record): LogRecord
    {
        if (null === self::$ip) {
            self::$ip = $this->getClientIp();
        }

        $record['extra']['client_ip'] = self::$ip;

        return $record;
    }

    private function getClientIp(): ?string
    {
        if (\array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return trim($ips[0]);
        }

        return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? null;
    }

    public function reset(): void
    {
        self::$ip = null;
    }
}
