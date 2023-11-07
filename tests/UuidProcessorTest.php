<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Handler\TestHandler;
use Monolog\Level;
use MonologProcessorCollection\Tests\AbstractProcessorTestCase;
use MonologProcessorCollection\UuidProcessor;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Uid\Uuid;

#[CoversClass(UuidProcessor::class)]
class UuidProcessorTest extends AbstractProcessorTestCase
{
    public function testItWorks(): void
    {
        $processor = new UuidProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $firstRecord = $handler->getRecords()[0];
        $secondRecord = $handler->getRecords()[1];

        $this->assertArrayHasKey('uuid', $firstRecord->extra);
        $this->assertTrue(Uuid::isValid($firstRecord->extra['uuid']));

        $this->assertArrayHasKey('uuid', $secondRecord->extra);
        $this->assertTrue(Uuid::isValid($secondRecord->extra['uuid']));

        $this->assertSame($firstRecord->extra['uuid'], $secondRecord->extra['uuid']);
    }

    public function testResettable(): void
    {
        $processor = new UuidProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $processor->reset();

        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $firstRecord = $handler->getRecords()[0];
        $secondRecord = $handler->getRecords()[1];

        $this->assertArrayHasKey('uuid', $firstRecord->extra);
        $this->assertTrue(Uuid::isValid($firstRecord->extra['uuid']));

        $this->assertArrayHasKey('uuid', $secondRecord->extra);
        $this->assertTrue(Uuid::isValid($secondRecord->extra['uuid']));

        $this->assertNotSame($firstRecord->extra['uuid'], $secondRecord->extra['uuid']);
    }
}
