@unit
Feature: Create user
  In order to register a score to an exercise
  As a anonymous user
  I need to be able to create an exercise

  Scenario: As a admin i should create new units, in order register different kinds of weights
    Given I am authenticated as admin
    And I have some data to send
      | unit_name | pounds |
      | unit_abbreviation | lbs |
    When I send a authenticated POST request to "/api/unit"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
          {
            "id": 2
          }
        """