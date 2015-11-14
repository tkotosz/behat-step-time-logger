<?php

namespace Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter;

use Behat\Testwork\Output\Printer\ConsoleOutputPrinter;
use Bex\Behat\StepTimeLoggerExtension\ServiceContainer\Config;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class Csv implements OutputPrinterInterface
{
    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @var ConsoleOutputPrinter
     */
    private $output;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param ConsoleOutputPrinter $output
     */
    public function __construct(ConsoleOutputPrinter $output, Filesystem $filesystem)
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
        $filePath = $this->createFile();
        $file = fopen($filePath, 'w');
        foreach ($avgTimes as $stepName => $time) {
            fputcsv($file, [$time, $calledCounts[$stepName], $stepName]);
        }
        
        fclose($file);

        $this->output->writeln('Step time log has been saved. Open at ' . $filePath);
    }

    private function createFile()
    {
        $currTime = time();
        $targetFile = $this->getTargetPath("debug_times-{$currTime}.csv");
        $this->ensureDirectoryExists(dirname($targetFile));
        $this->filesystem->dumpFile($targetFile, '');

        return $targetFile;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    private function getTargetPath($fileName)
    {
        $path = rtrim($this->outputDirectory, DIRECTORY_SEPARATOR);
        return empty($path) ? $fileName : $path . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @param string $directory
     *
     * @throws IOException
     */
    private function ensureDirectoryExists($directory)
    {
        try {
            if (!$this->filesystem->exists($directory)) {
                $this->filesystem->mkdir($directory, 0770);
            }
        } catch (IOException $e) {
            throw new \RuntimeException(sprintf('Cannot create directory "%s".', $directory));
        }
    }
}