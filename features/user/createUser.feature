@user
Feature: Create user
  In order for members to register a new score
  As a admin i need to create a new user

  Scenario: As a admin i should create new users, so that they can register scores
    Given I am authenticated as admin
    And I have some data to send
      | email | test@sample.com |
      | password | test1234 |
    When I send a authenticated POST request to "/api/user"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
          {
            "result": true
          }
        """

  Scenario: As a admin i'm trying to create an user with an invalid emailadres
    Given I am authenticated as admin
    And I have some data to send
      | email | abcd |
      | password | test1234 |
    When I send a authenticated POST request to "/api/user"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
          {
            "result": false,
            "errors": [
            "Er is een ongeldig emailadres opgegeven."
            ]
          }
        """

  Scenario: As a admin i'm trying to create an user that already exists
    Given I am authenticated as admin
    And I have some data to send
      | email | admin@example.com |
      | password | test1234 |
    When I send a authenticated POST request to "/api/user"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
          {
            "result": false,
            "errors": [
            "Er bestaat al een gebruiker met dit emailadres."
            ]
          }
        """