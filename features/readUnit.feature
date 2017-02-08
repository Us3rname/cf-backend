@unit
Feature: Read unit
  In order to register a weight
  As a anonymous user
  I need to know what kind of units i can select

  Scenario: As a user i should get all the units, in order to register weights in the right unit
    Given I am authenticated as user
    When I send a authenticated GET request to "/api/unit"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
          {
  "1": {
    "id": 1,
    "name": "kilogram",
    "abbreviation": "kg"
  },
  "2": {
    "id": 2,
    "name": "pounds",
    "abbreviation": "lbs"
  }
}
        """

  Scenario: As a user i should get all the kilogram unit, in order to register weights in kilograms
    Given I am authenticated as user
    When I send a authenticated GET request to "/api/unit/1"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
           {
    "id": 1,
    "name": "kilogram",
    "abbreviation": "kg"
  }
        """

  Scenario: As a user i should get all the pounds unit, in order to register weights in pounds
    Given I am authenticated as user
    When I send a authenticated GET request to "/api/unit/2"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
           {
    "id": 2,
    "name": "pounds",
    "abbreviation": "lbs"
  }
        """
