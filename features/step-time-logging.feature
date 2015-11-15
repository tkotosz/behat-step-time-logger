Feature: Logging step times
  In order to find slow scanerio steps
  As a developer
  I should see a step execution times

  Background:
    Given I have the feature:
      """
      Feature: Multi-step feature
      Scenario:
        Given I have a step which runs for "0.25" s
        When I have a step which runs for "0.5" s
        Then I have a step which runs for "0.1" s
      """
    And I have the context:
      """
      <?php
      use Behat\Behat\Context\Context;
      use Behat\Behat\Context\SnippetAcceptingContext;
      class FeatureContext implements Context, SnippetAcceptingContext
      {
          /**
           * @Given I have a step which runs for :time s
           */
          function iHaveAStepWhichRunsFor($time)
          {
              sleep($time);
          }
      }
      """

  Scenario: Print debug times to console
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\StepTimeLoggerExtension:
            enabled_always: true
      """
    When I run Behat
    Then I should see the step times on the console

  Scenario: Print debug times to csv
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\StepTimeLoggerExtension:
            enabled_always: true
            output: csv
      """
    When I run Behat
    Then I should see the message "Step time log has been saved. Open at %temp-dir%/steptimelogger/"