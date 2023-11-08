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
 * Add the value of some php.ini settings to the log record.
 */
final class PhpIniValueProcessor implements ProcessorInterface
{
    /**
     * @var array<string>
     */
    private array $settings;

    public function __construct(array|string $settings)
    {
        $this->settings = (array) $settings;
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $record['extra']['php_ini'] = [];

        foreach ($this->settings as $setting) {
            $record['extra']['php_ini'][$setting] = \ini_get($setting);
        }

        return $record;
    }
}
