<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Handler\TestHandler;
use Monolog\Level;
use MonologProcessorCollection\SessionIdProcessor;
use MonologProcessorCollection\Tests\AbstractProcessorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SessionIdProcessor::class)]
class SessionIdProcessorTest extends AbstractProcessorTestCase
{
    public function testItWorks(): void
    {
        $processor = new SessionIdProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);

        \session_start();
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('session_id', $record->extra);
        $this->assertNotNull($record->extra['session_id']);
    }

    public function testItWorksWithNullSession(): void
    {
        $processor = new SessionIdProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);

        \session_destroy();
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('session_id', $record->extra);
        $this->assertNull($record->extra['session_id']);
    }
}
