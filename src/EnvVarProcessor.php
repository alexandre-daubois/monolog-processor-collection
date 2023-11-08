<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MonologProcessorCollection;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Add the value of the environment variables to the log record.
 */
final class EnvVarProcessor implements ProcessorInterface
{
    /**
     * @var array<string>
     */
    private array $vars;

    public function __construct(array|string $vars)
    {
        $this->vars = (array) $vars;
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['env'] = [];

        foreach ($this->vars as $var) {
            $record['extra']['env'][$var] = $_ENV[$var] ?? $_SERVER[$var] ?? null;
        }

        return $record;
    }
}
