<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection\Tests;

use Monolog\Handler\TestHandler;
use Monolog\Level;
use MonologProcessorCollection\BacktraceProcessor;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(BacktraceProcessor::class)]
class BacktraceProcessorTest extends AbstractProcessorTestCase
{
    public function testItWorks(): void
    {
        $processor = new BacktraceProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('backtrace', $record->extra);
        $this->assertStringContainsString(__FILE__, $record->extra['backtrace'][0]);
        $this->assertStringMatchesFormat('%s/vendor/bin/phpunit(%d)', $record->extra['backtrace'][\count($record->extra['backtrace']) - 1]);
    }
}
