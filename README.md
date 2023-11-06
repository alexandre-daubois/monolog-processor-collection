# Monolog Processor Collection

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg?style=flat-square)](https://php.net/)
![CI](https://github.com/alexandre-daubois/monolog-processor-collection/actions/workflows/php.yml/badge.svg)
[![Latest Stable Version](http://poser.pugx.org/alexandre-daubois/monolog-processor-collection/v/stable)](https://packagist.org/packages/alexandre-daubois/monolog-processor-collection)
[![License](http://poser.pugx.org/alexandre-daubois/monolog-processor-collection/license)](https://packagist.org/packages/alexandre-daubois/monolog-processor-collection)

Monolog Processor Collectior, or MPC for short, is a collection of useful processors for the
[Monolog](https://github.com/Seldaek/monolog) logging library. The processors
add useful information to the log records. The package is compatible with PHP 8.1+.

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

The package provides the following processors:

- `BacktraceProcessor` adds the backtrace to the log record
- `EnvVarProcessor` adds the value of one or more environment variables to the log record
- `IsHttpsProcessor` adds a boolean value indicating whether the request is a secured HTTP request to the log record
- `PhpIniValueProcessor` adds the value of one or more PHP ini settings to the log record
- `ProtocolVersionProcessor` adds the HTTP protocol version to the log record
