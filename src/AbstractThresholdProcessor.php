<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection;

use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

abstract class AbstractThresholdProcessor implements ProcessorInterface
{
    public function __construct(private readonly Level $threshold = Level::Notice)
    {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        if ($record->level->value < $this->threshold->value) {
            return $record;
        }

        return static::process($record);
    }

    abstract protected function process(LogRecord $record): LogRecord;
}
