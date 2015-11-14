<?php

namespace Bex\Behat\StepTimeLogger\Service;

class StepTimeLogger
{
    /**
     * @var float
     */
    private $lastStepStartTime;

    /**
     * @var array
     */
    private $executionTimes = [];

    /**
     * @param string $stepName
     */
    public function logStepStarted($stepName)
    {
        $this->calledSteps[] = $stepName;
        $this->lastStepStartTime = microtime(true);
    }

    /**
     * @param string $stepName
     */
    public function logStepFinished($stepName)
    {
        $this->executionTimes[$stepName][] = microtime(true) - $this->lastStepStartTime;
    }

    /**
     * @return void
     */
    public function clearLogs()
    {
        $this->executionTimes = [];
    }

    /**
     * @return array
     */
    public function getCalledCounts()
    {
        return array_count_values($this->calledSteps);
    }

    /**
     * @return array
     */
    public function getAvegrageExecutionTimes()
    {
        $avgTimes = [];

        foreach ($this->executionTimes as $stepName => $executionTimes) {
            $avgTimes[$stepName] = array_sum($executionTimes) / count($executionTimes);
        }

        arsort($avgTimes);
        
        return $avgTimes;
    }    
}