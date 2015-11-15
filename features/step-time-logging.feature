Feature: Logging step times
  In order to find slow scanerio steps
  As a developer
  I should see a step execution times

  Background:
    Given I have the feature:
      """
      Feature: Multi-step feature
      Scenario:
        Given I am a developer
        When I have a step which runs for "1" s
        And I have a step which runs for "0.5" s
        Then I have a step which runs for "0" s
      """
    And I have the context:
      """
      <?php
      use Behat\Behat\Context\Context;
      use Behat\Behat\Context\SnippetAcceptingContext;
      class FeatureContext implements Context, SnippetAcceptingContext
      {
          /**
           * @Given I am a developer
           */
          function iAmADeveloper()
          {
              return true;
          }
          /**
           * @Given I have a step which runs for :time s
           */
          function iHaveAStepWhichRunsFor($time)
          {
              sleep((float)$time);
          }
      }
      """

  Scenario: Print debug times to console
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\StepTimeLoggerExtension: ~
      """
    When I run Behat with "--log-step-times" parameter
    Then I should see the step times on the console

  Scenario: Print debug times to csv
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\StepTimeLoggerExtension:
            output: csv
      """
    When I run Behat with "--log-step-times" parameter
    Then I should see the message "Step time log has been saved. Open at %temp-dir%/steptimelogger/"

  Scenario: Print debug times to csv to the configured folder
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\StepTimeLoggerExtension:
            output: csv
            output_directory: /tmp/testdirectory
      """
    When I run Behat with "--log-step-times" parameter
    Then I should see the message "Step time log has been saved. Open at /tmp/testdirectory"

  Scenario: Print debug times to csv and to console as well
    Given I have the configuration:
      """
      default:
        extensions:
          Bex\Behat\StepTimeLoggerExtension:
            output: [console, csv]
      """
    When I run Behat with "--log-step-times" parameter
    Then I should see the step times on the console
    And I should see the message "Step time log has been saved. Open at %temp-dir%/steptimelogger/"

  Scenario: Print debug times when the enabled_always config used
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
