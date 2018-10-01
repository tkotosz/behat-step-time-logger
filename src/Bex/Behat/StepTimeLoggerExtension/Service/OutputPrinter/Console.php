<?php

namespace Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter;

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
     * @param \Generator $avgTimes
     *
     * @return void
     */
    public function printLogs(\Generator $avgTimes)
    {
        $table = new Table($this->output);
        $table->setHeaders(['Average execution Time', 'Called count', 'Total Cost', 'Step name']);
        foreach ($avgTimes as $stepName => $info) {
            $table->addRow([$info['avg_execution_time'], $info['total_executions'], $info['total_cost'], $stepName]);
        }
        $table->render();
    }
}
