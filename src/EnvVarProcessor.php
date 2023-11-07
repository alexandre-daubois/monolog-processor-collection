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

/**
 * Add the value of the environment variables to the log record.
 */
final class EnvVarProcessor extends AbstractThresholdProcessor
{
    /**
     * @var array<string>
     */
    private array $vars;

    public function __construct(array|string $vars, Level $threshold = Level::Notice)
    {
        parent::__construct($threshold);

        $this->vars = (array) $vars;
    }

    protected function process(LogRecord $record): LogRecord
    {
        $record['extra']['env'] = [];

        foreach ($this->vars as $var) {
            $record['extra']['env'][$var] = $_ENV[$var] ?? $_SERVER[$var] ?? null;
        }

        return $record;
    }
}
