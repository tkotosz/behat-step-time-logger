<?php

namespace Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter;

use Bex\Behat\StepTimeLoggerExtension\ServiceContainer\Config;

interface OutputPrinterInterface
{
    const SERVICE_ID_PREFIX = 'bex.step_time_logger_extension.output_printer';

    /**
     * @param Config $config
     */
    public function configure(Config $config);

    /**
     * @param \Generator $avgTimes
     *
     * @return void
     */
    public function printLogs(\Generator $avgTimes);
}
