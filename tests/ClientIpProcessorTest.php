<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Handler\TestHandler;
use Monolog\Level;
use MonologProcessorCollection\ClientIpProcessor;
use MonologProcessorCollection\Tests\AbstractProcessorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ClientIpProcessor::class)]
class ClientIpProcessorTest extends AbstractProcessorTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testItWorksWithForward(): void
    {
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '127.0.0.1,127.0.0.2';
        $processor = new ClientIpProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('client_ip', $record->extra);
        $this->assertSame('127.0.0.1', $record->extra['client_ip']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testItWorksWithClientIp(): void
    {
        $_SERVER['HTTP_CLIENT_IP'] = '127.0.0.1';
        $processor = new ClientIpProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('client_ip', $record->extra);
        $this->assertSame('127.0.0.1', $record->extra['client_ip']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testItWorksWithRemoteAddress(): void
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $processor = new ClientIpProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('client_ip', $record->extra);
        $this->assertSame('127.0.0.1', $record->extra['client_ip']);
    }

    public function testItWorksOnNull(): void
    {
        $processor = new ClientIpProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('client_ip', $record->extra);
        $this->assertNull($record->extra['client_ip']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testResettable(): void
    {
        $_SERVER['HTTP_CLIENT_IP'] = '127.0.0.1';
        $processor = new ClientIpProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('client_ip', $record->extra);
        $this->assertNotNull($record->extra['client_ip']);

        $processor->reset();

        unset($_SERVER['HTTP_CLIENT_IP']);
        $handler->handle($this->createRecord(Level::Notice));
        $record = $handler->getRecords()[1];
        $this->assertNull($record->extra['client_ip']);
    }
}
