Feature: List and show sessions
  In order to browse sessions
  As a user
  I want to retrieve sessions

  @fixtures
  Scenario: List sessions without authentication
    Given I have a Session with values
      | title       | Test Session for Dummies   |
      | description | Lorem ipsum dolor sit amet |
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions"
    Then the response code should be 200
    And the response should contain the json subset:
      """
      [{
        "title": "Test Session for Dummies",
        "description": "Lorem ipsum dolor sit amet"
      }]
      """

  @fixtures
  Scenario: List sessions as authenticated user
    Given I have a Session with values
      | title       | Test Session for Dummies   |
      | description | Lorem ipsum dolor sit amet |
    And I am a user "foo" with password "bar" and role "Authenticated"
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions"
    Then the response code should be 200
    And the response should contain the json subset:
      """
      [{
        "title": "Test Session for Dummies",
        "description": "Lorem ipsum dolor sit amet"
      }]
      """

  @fixtures
  Scenario: Retrieve a single session
    Given I have a Session with values
      | title       | Single Test Session        |
      | description | Lorem ipsum dolor sit amet |
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions/single-test-session"
    Then the response code should be 200
    And the response should contain json:
      """
      {
        "title": "Single Test Session",
        "description": "Lorem ipsum dolor sit amet"
      }
      """

  @fixtures
  Scenario: Retrieve session list with multiple sessions
     Given I have a Session with values
      | title       | Test Session for Dummies   |
      | description | Lorem ipsum dolor sit amet |
     Given I have a Session with values
      | title       | Test Session for Experts |
      | description | Foo bar baz              |
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions"
    Then the response code should be 200
    And the response should contain the json subset:
      """
      [{
        "title": "Test Session for Dummies",
        "description": "Lorem ipsum dolor sit amet"
      },
      {
        "title": "Test Session for Experts",
        "description": "Foo bar baz"
      }]
      """
