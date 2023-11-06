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
use MonologProcessorCollection\EnvVarProcessor;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(EnvVarProcessor::class)]
class EnvVarProcessorTest extends AbstractProcessorTestCase
{
    public function testItWorks(): void
    {
        $processor = new EnvVarProcessor('HOME');

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];
        $this->assertSame(\getenv('HOME'), $record->extra['env']['HOME']);
    }

    public function testItWorksWithArray(): void
    {
        $processor = new EnvVarProcessor(['HOME', 'USER']);

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];
        $this->assertSame(\getenv('HOME'), $record->extra['env']['HOME']);
        $this->assertSame(\getenv('USER'), $record->extra['env']['USER']);
    }
}
