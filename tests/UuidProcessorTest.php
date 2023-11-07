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

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('uuid', $record->extra);
        $this->assertTrue(Uuid::isValid($record->extra['uuid']));
    }
}
