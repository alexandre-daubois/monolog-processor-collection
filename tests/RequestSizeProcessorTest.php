<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Handler\TestHandler;
use Monolog\Level;
use MonologProcessorCollection\RequestSizeProcessor;
use MonologProcessorCollection\Tests\AbstractProcessorTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RequestSizeProcessor::class)]
class RequestSizeProcessorTest extends AbstractProcessorTestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testItWorks(): void
    {
        $processor = new RequestSizeProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);

        $_SERVER['HTTP_USER_AGENT'] = 'My agent';

        $handler->handle($this->createRecord(Level::Notice));
        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('request_size', $record->extra);
        $this->assertEquals(\strlen('USER_AGENT') + \strlen('My agent'), $record->extra['request_size']['headers']);
    }

    public function testResettable(): void
    {
        $processor = new RequestSizeProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);

        $_SERVER['HTTP_USER_AGENT'] = 'My agent';

        $handler->handle($this->createRecord(Level::Notice));
        $record = $handler->getRecords()[0];

        $this->assertEquals(\strlen('USER_AGENT') + \strlen('My agent'), $record->extra['request_size']['headers']);

        $processor->reset();

        $_SERVER['HTTP_USER_AGENT'] = 'My agent 2';
        $handler->handle($this->createRecord(Level::Notice));
        $record = $handler->getRecords()[1];
        $this->assertEquals(\strlen('USER_AGENT') + \strlen('My agent 2'), $record->extra['request_size']['headers']);
    }
}
