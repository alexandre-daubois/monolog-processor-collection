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
use MonologProcessorCollection\IsHttpsProcessor;
use MonologProcessorCollection\ProtocolVersionProcessor;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IsHttpsProcessor::class)]
class IsHttpsProcessorTest extends AbstractProcessorTestCase
{
    public function testItWorks(): void
    {
        $processor = new IsHttpsProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('is_https', $record->extra);
        $this->assertFalse($record->extra['is_https']);
    }
}
