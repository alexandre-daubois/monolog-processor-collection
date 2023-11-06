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
use MonologProcessorCollection\PhpIniValueProcessor;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PhpIniValueProcessor::class)]
class PhpIniValueProcessorTest extends AbstractProcessorTestCase
{
    public function testItWorks(): void
    {
        $processor = new PhpIniValueProcessor('date.timezone');

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('php_ini', $record->extra);
        $this->assertArrayHasKey('date.timezone', $record->extra['php_ini']);
        $this->assertSame(\ini_get('date.timezone'), $record->extra['php_ini']['date.timezone']);
    }

    public function testItWorksWithArray(): void
    {
        $processor = new PhpIniValueProcessor(['date.timezone', 'memory_limit']);

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('php_ini', $record->extra);
        $this->assertArrayHasKey('date.timezone', $record->extra['php_ini']);
        $this->assertSame(\ini_get('date.timezone'), $record->extra['php_ini']['date.timezone']);
        $this->assertArrayHasKey('memory_limit', $record->extra['php_ini']);
        $this->assertSame(\ini_get('memory_limit'), $record->extra['php_ini']['memory_limit']);
    }
}
