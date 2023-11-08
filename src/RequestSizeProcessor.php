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
 * Add the request size to the log record.
 */
final class RequestSizeProcessor implements ProcessorInterface, ResettableInterface
{
    private static ?array $size = null;

    public function __invoke(LogRecord $record): LogRecord
    {
        if (null === self::$size) {
            $headers = $this->getHeaders();
            $headersSize = \array_sum(
                \array_map(\strlen(...), \array_keys($headers))
            ) + \array_sum(
                \array_map(\strlen(...), $headers)
            );

            $bodySize = \strlen(\file_get_contents('php://input'));
            self::$size = [
                'headers' => $headersSize,
                'body' => $bodySize,
                'total' => $headersSize + $bodySize,
            ];
        }

        $record['extra']['request_size'] = self::$size;

        return $record;
    }

    /**
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
        if (\function_exists('apache_request_headers')) {
            return \apache_request_headers();
        }

        $headers = [];
        foreach ($_SERVER as $k => $v) {
            if (\str_starts_with($k, 'HTTP_')) {
                $headers[\substr($k, 5)] = $v;
            }
        }

        return $headers;
    }

    public function reset(): void
    {
        self::$size = null;
    }
}
