Behat-StepTimeLoggerExtension
=========================
[![License](https://poser.pugx.org/bex/behat-step-time-logger/license)](https://packagist.org/packages/bex/behat-step-time-logger)
[![Latest Stable Version](https://poser.pugx.org/bex/behat-step-time-logger/version)](https://packagist.org/packages/bex/behat-step-time-logger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tkotosz/behat-step-time-logger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/behat-step-time-logger/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tkotosz/behat-step-time-logger/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tkotosz/behat-step-time-logger/build-status/master)
[![Build Status](https://travis-ci.org/tkotosz/behat-step-time-logger.svg?branch=master)](https://travis-ci.org/tkotosz/behat-step-time-logger)

Behat-StepTimeLoggerExtension helps you find slow behat scenario steps by logging the step times.

Installation
------------

Install by adding to your `composer.json`:

```bash
composer require --dev bex/behat-step-time-logger
```

Configuration
-------------

Enable the extension in `behat.yml` like this:

```yml
default:
  extensions:
    Bex\Behat\StepTimeLoggerExtension: ~
```

You can configure which output printer should be used:
```yml
default:
  extensions:
    Bex\Behat\StepTimeLoggerExtension:
      output: csv # possible values: console, csv. default value: console
```

You can even enable both output printer at once:
```yml
default:
  extensions:
    Bex\Behat\StepTimeLoggerExtension:
      output: [console, csv]
```

You can enable the logger to run every time even if you don't use the --log-step-times flag:
```yml
default:
  extensions:
    Bex\Behat\StepTimeLoggerExtension:
      enabled_always: true
```

You can configure the output directory of the csv printer as well:
```yml
ci:
  extensions:
    Bex\Behat\StepTimeLoggerExtension:
      output_directory: /your/path/for/the/csvfile # by default it will be saved to the /tmp/steptimelogger directory
```

Usage
-----

When debugging a particular scenario, use the `--log-step-times` flag at the cli:

```bash
bin/behat --log-step-times
```

Or use the `enabled_always` config setting to run the logger every time. (See configuration section)

Example output
--------------

```bash
+------------------------+--------------+-----------------------------------------------------+
| Average execution Time | Called count | Total Cost | Step name                              |
+------------------------+--------------+-----------------------------------------------------+
| 1.73161                | 2            | 3.46322    | I am on page "test-product.html"       |
| 0.30817                | 1            | 0.30817    | I should see "$99,999.00" as the price |
| 0.05326                | 2            | 0.10652    | I should see "Add to Compare"          |
+------------------------+--------------+-----------------------------------------------------+
```

```bash
Step time log has been saved. Open at /tmp/steptimelogger/step-times-1447580698.csv
```
