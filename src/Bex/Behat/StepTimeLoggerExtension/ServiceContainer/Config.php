<?php

namespace Bex\Behat\StepTimeLoggerExtension\ServiceContainer;

use Bex\Behat\StepTimeLoggerExtension\Service\OutputPrinter\OutputPrinterInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Config
{
    const CONFIG_KEY_ENABLED_ALWAYS = 'enabled_always';
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
        $this->enabled = $config[self::CONFIG_KEY_ENABLED_ALWAYS];
        $this->outputDirectory = $config[self::CONFIG_KEY_OUTPUT_DIRECTORY];
        $this->format = $config[self::CONFIG_KEY_FORMAT];
        
    }

    public function enableLogging()
    {
        $this->enabled = true;
    }

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
     * @return void
     */
    public function getOutputPrinter()
    {
        return $this->container->get(OutputPrinterInterface::SERVICE_ID_PREFIX . '.' . $this->format);
    }
}