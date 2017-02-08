@exercise
Feature: Create exercise
  In order to register a score to an exercise
  As a anonymous user
  I need to be able to create an exercise

  Scenario: Anonymous user should create exercise
    Given I am authenticated as admin
    And I have some data to send
      | name_exercise | snatch |
    When I send a authenticated POST request to "/api/exercise"
    Then the guzzle response status code should be 200
    And the response should contain "bla"
    And the response entry "message" should be "created"


