<?php

namespace Bex\Behat\StepTimeLoggerExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension;
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
    const CONFIG_KEY = 'steptimelogger';

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
                ->booleanNode('enabled_always')
                    ->defaultFalse()
                ->end()
                ->scalarNode('output_directory')
                    ->defaultValue(sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::CONFIG_KEY)
                ->end()
                ->arrayNode('output')
                    ->defaultValue(['console'])
                    ->beforeNormalization()
                        ->always($this->getOutputTypeInitializer())
                    ->end()
                    ->validate()
                        ->always($this->getOutputTypeValidator())
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/config'));
        $loader->load('services.xml');

        $extensionConfig = new Config($container, $config);
        $container->set('bex.step_time_logger_extension.config', $extensionConfig);
    }

    /**
     * @return \Closure
     */
    private function getOutputTypeInitializer()
    {
        return function ($value) {
            $value = empty($value) ? ['console'] : $value;
            return is_array($value) ? $value : [$value];
        };
    }

    /**
     * @return \Closure
     */
    private function getOutputTypeValidator()
    {
        return function ($value) {
            $allowed = ['console', 'csv'];
            $invalid = array_diff($value, $allowed);
            
            if (!empty($invalid)) {
                $message = 'Invalid output types: %s. Allowed types: %s';
                throw new \InvalidArgumentException(sprintf($message, join(',', $invalid), join(',', $allowed)));
            }

            return $value;
        };
    }
}