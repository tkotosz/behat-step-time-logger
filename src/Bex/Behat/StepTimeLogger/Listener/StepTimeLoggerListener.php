<?php

namespace Bex\Behat\StepTimeLogger\Listener;

use Behat\Behat\EventDispatcher\Event\AfterStepTested;
use Behat\Behat\EventDispatcher\Event\BeforeStepTested;
use Behat\Behat\EventDispatcher\Event\StepTested;
use Behat\Testwork\EventDispatcher\Event\AfterSuiteTested;
use Behat\Testwork\EventDispatcher\Event\SuiteTested;
use Bex\Behat\StepTimeLogger\Service\OutputPrinter\OutputPrinterInterface;
use Bex\Behat\StepTimeLogger\Service\StepTimeLogger;

final class StepTimeLoggerListener implements EventSubscriberInterface
{
    /**
     * @var StepTimeLogger
     */
    private $stepTimeLogger;

    /**
     * @var OutputPrinterInterface
     */
    private $outputPrinter;

    /**
     * @param StepTimeLogger         $stepTimeLogger
     * @param OutputPrinterInterface $outputPrinter
     */
    public function __construct(StepTimeLogger $stepTimeLogger, OutputPrinterInterface $outputPrinter)
    {
        $this->stepTimeLogger = $stepTimeLogger;
        $this->outputPrinter = $outputPrinter;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            StepTested::BEFORE => 'stepStarted',
            StepTested::AFTER => 'stepFinished',
            SuiteTested::AFTER => 'suiteFinished'
        ];
    }

    /**
     * @param  BeforeStepTested $event
     */
    public function stepStarted(BeforeStepTested $event)
    {
        $this->stepTimeLogger->logStepStarted();
    }

    /**
     * @param  AfterStepTested $event
     */
    public function stepFinished(AfterStepTested $event)
    {
        $this->stepTimeLogger->logStepFinished($event->getStep()->getText());
    }

    /**
     * @param  AfterSuiteTested $event
     */
    public function suiteFinished(AfterSuiteTested $event)
    {
        $this->outputPrinter->printLogs(
            $this->stepTimeLogger->getCalledCounts(),
            $this->stepTimeLogger->getAvegrageExecutionTimes()
        );
        $this->stepTimeLogger->clearLogs();
    }
}