@user
Feature: Get users

  Scenario: As a admin i should get an overview of all the users, in order to edit a specific user.
    Given I am authenticated as admin
    When I send a authenticated GET request to "/api/user"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
           {
    "id": -2,
    "username": "alice",
    "username_canonical": "alice",
    "email": "alice@example.com",
    "email_canonical": "alice@example.com",
    "enabled": true,
    "salt": null,
    "password": "$2y$13$hFfwOe5HdkllwiU4FHLi3uT5JfhOMLabeP18xRBEEB2.8pf.Gzto2",
    "plain_password": null,
    "last_login": null,
    "confirmation_token": null,
    "password_requested_at": null,
    "groups": [],
    "roles": []
  },
  {
    "id": -1,
    "username": "admin",
    "username_canonical": "admin",
    "email": "admin@example.com",
    "email_canonical": "admin@example.com",
    "enabled": true,
    "salt": null,
    "password": "$2y$13$T2TSLBt0d64mmAuVWQRZDO2MHRqYrhsD20VGoamkxrERCGGxMVbza",
    "plain_password": null,
    "last_login": null,
    "confirmation_token": null,
    "password_requested_at": null,
    "groups": null,
    "roles": []
  }
        """

  Scenario: As a admin i want to view the admin user details
    Given I am authenticated as admin
    When I send a authenticated GET request to "/api/user/-1"
    Then the guzzle response status code should be 200
    And the guzzle response header should be json
    And the response contains the following values from JSON:
       """
          {
  "id": -1,
  "username": "admin",
  "username_canonical": "admin",
  "email": "admin@example.com",
  "email_canonical": "admin@example.com",
  "enabled": true,
  "salt": null,
  "password": "$2y$13$T2TSLBt0d64mmAuVWQRZDO2MHRqYrhsD20VGoamkxrERCGGxMVbza",
  "plain_password": null,
  "last_login": null,
  "confirmation_token": null,
  "password_requested_at": null,
  "groups": null,
  "roles": []
}
        """

