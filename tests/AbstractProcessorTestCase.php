<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection\Tests;

use Monolog\Level;
use Monolog\LogRecord;
use MonologProcessorCollection\BacktraceProcessor;
use PHPUnit\Framework\TestCase;

abstract class AbstractProcessorTestCase extends TestCase
{
    public function testLogUnderNoticeLevelsSkips(): void
    {
        $processor = new BacktraceProcessor();

        $record = $processor($this->createRecord(Level::Debug));
        $this->assertArrayNotHasKey('backtrace', $record->extra);
    }

    protected function createRecord(Level $level): LogRecord
    {
        return new LogRecord(new \DateTimeImmutable(), 'main', $level, 'notice');
    }
}
