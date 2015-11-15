<?php

namespace Bex\Behat\StepTimeLoggerExtension\ServiceContainer;

use Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter\OutputPrinterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Config
{
    const CONFIG_KEY_ENABLED_ALWAYS = 'enabled_always';
    const CONFIG_KEY_OUTPUT_DIRECTORY = 'output_directory';
    const CONFIG_KEY_FORMAT = 'output';

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @var array
     */
    private $outputFormats;

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function __construct(ContainerBuilder $container, $config)
    {
        $this->container = $container;
        $this->enabled = $config[self::CONFIG_KEY_ENABLED_ALWAYS];
        $this->outputDirectory = $config[self::CONFIG_KEY_OUTPUT_DIRECTORY];
        $this->outputFormats = $config[self::CONFIG_KEY_FORMAT];
    }

    /**
     * Activate the extension
     * 
     * @return void
     */
    public function enableLogging()
    {
        $this->enabled = true;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getOutputDirectory()
    {
        return $this->outputDirectory;
    }

    /**
     * @return OutputPrinterInterface[]
     */
    public function getOutputPrinters()
    {
        $printers = [];

        foreach ($this->outputFormats as $format) {
            $printer = $this->container->get(OutputPrinterInterface::SERVICE_ID_PREFIX . '.' . $format);
            $printer->configure($this);
            $printers[] = $printer;
        }

        return $printers;
    }
}