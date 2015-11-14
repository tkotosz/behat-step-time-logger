<?php

namespace Bex\Behat\StepTimeLogger\Service\OutputPrinter;

use Bex\Behat\StepTimeLogger\ServiceContainer\Config;
use Symfony\Component\Console\Helper\Table;

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
        $table = new Table(new Behat\Testwork\Output\Printer\ConsoleOutputPrinter());
        $table->setHeaders(array('Execution Time', 'Called count', 'Step name'));
        foreach ($avgTimes as $stepName => $time) {
            $table->addRow([$time, $calledCounts[$stepName], $stepName]);
        }
        $table->render();
    }
}