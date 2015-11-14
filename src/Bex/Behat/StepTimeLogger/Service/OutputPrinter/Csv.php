<?php

namespace Bex\Behat\StepTimeLogger\Service\OutputPrinter;

class Csv implements OutputPrinterInterface
{
    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @param Config $config
     */
    public function configure(Config $config)
    {
        $this->outputDirectory = $config->getOutputDirectory();
    }

    /**
     * @param  array $calledCounts
     * @param  array $avgTimes
     *
     * @return void
     */
    public function printLogs(array $calledCounts, array $avgTimes)
    {
        $currTime = time();

        $file = fopen(rtrim($this->outputDirectory) . DIRECTORY_SEPARATOR . "debug_times-{$currTime}.csv", 'w');
        foreach ($avgTimes as $stepName => $time) {
            fputcsv($file, [$time, $calledCounts[$stepName], $stepName]);
        }
        fclose($f);
    }
}