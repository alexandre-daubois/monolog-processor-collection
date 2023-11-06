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

final class PhpIniValueProcessor extends AbstractThresholdProcessor
{
    /**
     * @var array<string>
     */
    private array $settings;

    public function __construct(array|string $settings, Level $threshold = Level::Notice)
    {
        parent::__construct($threshold);

        $this->settings = (array) $settings;
    }

    protected function process(LogRecord $record): LogRecord
    {
        $record['extra']['php_ini'] = [];

        foreach ($this->settings as $setting) {
            $record['extra']['php_ini'][$setting] = \ini_get($setting);
        }

        return $record;
    }
}
