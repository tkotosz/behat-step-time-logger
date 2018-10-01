<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var TestRunnerContext $testRunnerContext */
    private $testRunnerContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->testRunnerContext = $environment->getContext('Bex\Behat\Context\TestRunnerContext');
    }

    /**
     * @Then I should see the message :message
     */
    public function iShouldSeeTheMessage($message)
    {
        $message = $this->substituteParameters($message);
        $output = $this->testRunnerContext->getStandardOutputMessage() .
            $this->testRunnerContext->getStandardErrorMessage();
        $this->assertOutputContainsMessage($output, $message);
    }

    /**
     * @Then I should see the step times on the console
     */
    public function iShouldSeeTheStepTimesOnTheConsole()
    {
       $this->iShouldSeeTheMessage('| Average execution Time | Called count | Total Cost | Step name');
    }

    /**
     * @param $text
     * @return mixed
     */
    private function substituteParameters($text)
    {
        return str_replace('%temp-dir%', sys_get_temp_dir(), $text);
    }

    /**
     * @param $message
     */
    private function assertOutputContainsMessage($output, $message)
    {
        if (mb_strpos($output, $message) === false) {
            throw new RuntimeException('Behat output did not contain the given message.');
        }
    }
}
