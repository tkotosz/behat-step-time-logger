<?php

namespace Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter;

use Bex\Behat\StepTimeLoggerExtension\Service\StepTimeLogger;
use Bex\Behat\StepTimeLoggerExtension\ServiceContainer\Config;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;

class Csv implements OutputPrinterInterface
{
    const FILE_NAME_PATTERN = 'step-times-%s.csv';

    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @var ConsoleOutput
     */
    private $output;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param ConsoleOutput $output
     */
    public function __construct(ConsoleOutput $output, Filesystem $filesystem)
    {
        $this->output = $output;
        $this->filesystem = $filesystem;
    }

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
        $filePath = $this->getFilePath();
        $this->filesystem->dumpFile($filePath, '');
        $file = fopen($filePath, 'w');

        fputcsv($file, ['Average execution Time', 'Called count', 'Step name']);

        foreach ($avgTimes as $stepName => $time) {
            if (StepTimeLogger::TOTAL_TIME == $stepName) {
                fputcsv($file, [$time, 1, $stepName]);
            } else {
                fputcsv($file, [$time, $calledCounts[$stepName], $stepName]);
            }
        }

        fclose($file);

        $this->output->writeln('Step time log has been saved. Open at '.$filePath);
    }

    /**
     * @return string
     */
    private function getFilePath()
    {
        $fileName = sprintf(self::FILE_NAME_PATTERN, time());
        $path = rtrim($this->outputDirectory, DIRECTORY_SEPARATOR);

        return empty($path) ? $fileName : $path.DIRECTORY_SEPARATOR.$fileName;
    }
}
