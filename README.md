# Monolog Processor Collection

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg?style=flat-square)](https://php.net/)
![CI](https://github.com/alexandre-daubois/monolog-processor-collection/actions/workflows/php.yml/badge.svg)
[![Latest Stable Version](http://poser.pugx.org/alexandre-daubois/monolog-processor-collection/v/stable)](https://packagist.org/packages/alexandre-daubois/monolog-processor-collection)
[![License](http://poser.pugx.org/alexandre-daubois/monolog-processor-collection/license)](https://packagist.org/packages/alexandre-daubois/monolog-processor-collection)

Welcome to the Monolog Processor Collection (MPC) - the ultimate suite of processors designed to enhance your logging
with the renowned [Monolog](https://github.com/Seldaek/monolog) library. This toolkit is meticulously crafted to
integrate seamlessly with PHP 8.1+, ensuring your logging captures the comprehensive details you need with minimal
overhead.

MPC is engineered for developers who demand more from their logs. Whether you're tracking down elusive bugs or
monitoring live production environments, processors enrich your log entries with invaluable context, turning
ordinary logs into a rich, actionable dataset.

## Installation

The recommended way to install MPC is through [Composer](https://getcomposer.org/):

```bash
composer require alexandre-daubois/monolog-processor-collection
```

## Usage

All processor can be used in the same way as any other Monolog processor. For example:

```php
use Monolog\Logger;

$logger = new Logger('name');
$logger->pushProcessor(new BacktraceProcessor());
```

For performance reasons, processors use a threshold level to determine whether to add the information to the log record.
The default threshold level is `Level::Notice`. You can change the threshold level by passing it as the first
argument to the processor constructor. For example:

```php
use Monolog\Logger;
use Monolog\Level;

$logger = new Logger('name');
$logger->pushProcessor(new BacktraceProcessor(Level::Notice));
```

Some processors, like `EnvVarProcessor` and `PhpIniValueProcessor`, require you to specify more
arguments. For example:

```php
use Monolog\Logger;

$logger = new Logger('name');
$logger->pushProcessor(new EnvVarProcessor(['APP_ENV', 'APP_DEBUG']));
```

The package provides the following processors:

- `BacktraceProcessor` adds the backtrace to the log record
- `EnvVarProcessor` adds the value of one or more environment variables to the log record
- `HighResolutionTimestampProcessor` adds the high resolution time to the log record
- `IsHttpsProcessor` adds a boolean value indicating whether the request is a secured HTTP request to the log record
- `PhpIniValueProcessor` adds the value of one or more PHP ini settings to the log record
- `ProtocolVersionProcessor` adds the HTTP protocol version to the log record
- `ResourceUsagesProcessor` adds the resource usage to the log record as returned by [getrusage()](https://www.php.net/manual/en/function.getrusage.php)
- `SapiNameProcessor` adds the name of the SAPI to the log record
- `UuidProcessor` adds a UUID v7 to the log record to track records triggered during the same request

## Integration with Symfony and MonologBundle

You can register those processors to be used with Symfony and MonologBundle by adding the following configuration to
your `config/packages/monolog.php` file:

```php
use Monolog\Processor\ProcessorInterface;

return static function (ContainerConfigurator $configurator): void {
    // ...

    // register as many processors as you like, but keep in mind that
    // each processor is called for each log record
    $services = $configurator->services();
    $services
        ->set(BacktraceProcessor::class)
        ->set(EnvVarProcessor::class)->args(['APP_ENV'])
        ->set(ProtocolVersionProcessor::class)
        ->set(SapiNameProcessor::class);

    // ...
};
```

If you don't use autoconfigure, you need to tag the processors with `monolog.processor`:

```php
use Monolog\Processor\ProcessorInterface;
use MonologProcessorCollection\BacktraceProcessor;
use MonologProcessorCollection\EnvVarProcessor;

return static function (ContainerConfigurator $configurator): void {
    // ...

    $services = $configurator->services();
    $services
        ->set(BacktraceProcessorAlias::class)
            ->tag('monolog.processor', ['handler' => 'main'])
        ->set(EnvVarProcessor::class)->args(['APP_ENV'])
            ->tag('monolog.processor', ['handler' => 'main']);

    // ...
};
```

You can achieve the same configuration with YAML:

```yaml
# config/packages/monolog.yaml
services:
    Monolog\Processor\BacktraceProcessor:
        tags:
            - { name: monolog.processor, handler: main }
    Monolog\Processor\EnvVarProcessor:
        arguments:
            - APP_ENV
        tags:
            - { name: monolog.processor, handler: main }
```

Or XML:

```xml
<!-- config/packages/monolog.xml -->

<!-- ... -->

<service id="Monolog\Processor\BacktraceProcessor" public="false">
    <tag name="monolog.processor" handler="main" />
</service>
<service id="Monolog\Processor\EnvVarProcessor" public="false">
    <argument>APP_ENV</argument>
    <tag name="monolog.processor" handler="main" />
</service>
```
