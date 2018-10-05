<?php

namespace Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter;

use Bex\Behat\StepTimeLoggerExtension\Service\StepTimeLogger;
use Bex\Behat\StepTimeLoggerExtension\ServiceContainer\Config;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class Console implements OutputPrinterInterface
{
    /**
     * @var ConsoleOutput
     */
    private $output;

    /**
     * @param ConsoleOutput $output
     */
    public function __construct(ConsoleOutput $output)
    {
        $this->output = $output;
    }

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
        $table = new Table($this->output);
        $table->setHeaders(['Average execution Time', 'Called count', 'Step name']);
        foreach ($avgTimes as $stepName => $time) {
            if (StepTimeLogger::TOTAL_TIME == $stepName) {
                $table->addRow([$time, 1, $stepName]);

            } else {
                $table->addRow([$time, $calledCounts[$stepName], $stepName]);
            }
        }
        $table->render();
    }
}