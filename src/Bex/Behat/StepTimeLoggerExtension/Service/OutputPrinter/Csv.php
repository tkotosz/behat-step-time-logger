<?php

namespace Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter;

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
     * @param \Generator $avgTimes
     *
     * @return void
     */
    public function printLogs(\Generator $avgTimes)
    {
        $filePath = $this->getFilePath();
        $this->filesystem->dumpFile($filePath, '');
        $file = fopen($filePath, 'w');

        if ($file === false) {
            throw new \InvalidArgumentException(sprintf('Cannot open %s for writting', $filePath));
        }

        fputcsv($file, ['Average execution Time', 'Called count', 'Total Cost', 'Step name']);

        foreach ($avgTimes as $stepName => $info) {
            fputcsv($file, [$info['avg_execution_time'], $info['total_executions'], $info['total_cost'], $stepName]);
        }

        fclose($file);

        $this->output->writeln('Step time log has been saved. Open at ' . $filePath);
    }

    /**
     * @return string
     */
    private function getFilePath()
    {
        $fileName = sprintf(self::FILE_NAME_PATTERN, time());
        $path = rtrim($this->outputDirectory, DIRECTORY_SEPARATOR);
        return empty($path) ? $fileName : $path . DIRECTORY_SEPARATOR . $fileName;
    }


}
