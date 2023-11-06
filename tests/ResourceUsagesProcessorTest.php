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
use MonologProcessorCollection\ResourceUsagesProcessor;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ResourceUsagesProcessor::class)]
class ResourceUsagesProcessorTest extends AbstractProcessorTestCase
{
    public function testItWorks(): void
    {
        $processor = new ResourceUsagesProcessor();

        $handler = new TestHandler();
        $handler->pushProcessor($processor);
        $handler->handle($this->createRecord(Level::Notice));

        $this->assertTrue($handler->hasNoticeRecords());
        $record = $handler->getRecords()[0];

        $this->assertArrayHasKey('resource_usages', $record->extra);
        $this->assertIsArray($record->extra['resource_usages']);
        $this->assertArrayHasKey('ru_utime.tv_usec', $record->extra['resource_usages']);
        $this->assertArrayHasKey('ru_stime.tv_usec', $record->extra['resource_usages']);
    }
}
