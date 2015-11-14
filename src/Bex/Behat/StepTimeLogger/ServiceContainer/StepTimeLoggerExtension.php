<?php

namespace Bex\Behat\StepTimeLogger\ServiceContainer;

use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This class is the entry point of the step time logger extension
 *
 * @license http://opensource.org/licenses/MIT The MIT License
 */
class StepTimeLoggerExtension implements Extension
{
    const CONFIG_KEY = 'behat-step-time-logger';

     /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return self::CONFIG_KEY;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // nothing to do here
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        // nothing to do here
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                ->end()
                ->scalarNode('output_directory')
                    ->defaultValue(sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::CONFIG_KEY)
                ->end()
                ->enumNode('fromat')
                    ->values(['console', 'csv'])
                    ->defaultValue('console')
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $extensionConfig = new Config($container, $config);

        if ($extensionConfig->isExtensionEnabled()) {
            $loader->load('services.xml');
            $extensionConfig->configureOutputPrinter();
        }
    }
}