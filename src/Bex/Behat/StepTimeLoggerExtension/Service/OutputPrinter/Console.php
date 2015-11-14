<?php

namespace Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter;

use Bex\Behat\StepTimeLoggerExtension\ServiceContainer\Config;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class Console implements OutputPrinterInterface
{
    /**
     * @param Config $config
     */
    public function configure(Config $config)
    {
        // no configuration required
    }

    /**
     * @param  array $calledCounts
     * @param  array $avgTimes
     *
     * @return void
     */
    public function printLogs(array $calledCounts, array $avgTimes)
    {
        $table = new Table(new ConsoleOutput());
        $table->setHeaders(array('Average execution Time', 'Called count', 'Step name'));
        foreach ($avgTimes as $stepName => $time) {
            $table->addRow([$time, $calledCounts[$stepName], $stepName]);
        }
        $table->render();
    }
}