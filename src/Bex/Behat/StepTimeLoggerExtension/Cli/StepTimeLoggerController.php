<?php

namespace Bex\Behat\StepTimeLoggerExtension\Cli;

use Behat\Testwork\Cli\Controller;
use Bex\Behat\StepTimeLoggerExtension\ServiceContainer\Config;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class StepTimeLoggerController implements Controller
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;    
    }

    /**
     * Configures command to be executable by the controller.
     *
     * @param SymfonyCommand $command
     */
    public function configure(SymfonyCommand $command)
    {
        $command->addOption('--log-step-times', null, InputOption::VALUE_NONE, 'Log step times');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('log-step-times')) {
            $this->config->enableLogging();
        }
    }
}