<?php

namespace Bex\Behat\StepTimeLogger\ServiceContainer;

use Bex\Behat\StepTimeLogger\Service\OutputPrinterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Config
{
    const CONFIG_KEY_ENABLED = 'enabled';
    const CONFIG_KEY_OUTPUT_DIRECTORY = 'output_directory';
    const CONFIG_KEY_FORMAT = 'format';

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
     * @var string
     */
    private $format;

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function __construct(ContainerBuilder $container, $config)
    {
        $this->container = $container;
        $this->enabled = $config[self::CONFIG_KEY_ENABLED];
        $this->outputDirectory = $config[self::CONFIG_KEY_OUTPUT_DIRECTORY];
        $this->format = $config[self::CONFIG_KEY_FORMAT];
        
    }

    /**
     * @return boolean
     */
    public function isExtensionEnabled()
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
     * @return void
     */
    public function configureOutputPrinter()
    {
        $outputPrinter = $this->container->get(OutputPrinterInterface::SERVICE_ID_PREFIX . '.' . $this->format);
        $outputPrinter->configure($this);
        $container->setDefinition('bex.step_time_logger_extension.output_printer.current', $outputPrinter);
    }
}