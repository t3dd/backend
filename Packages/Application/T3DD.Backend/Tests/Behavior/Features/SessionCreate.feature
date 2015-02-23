Feature: Create sessions
  In order to create sessions
  As a authenticated user
  I want to submit sessions

  @fixtures
  Scenario: Create a new session
    Given I am a user "foo" with password "bar" and role "Authenticated"
    And I set header "Content-Type" with value "application/json"
    And I set header "Accept" with value "application/json"
    When I send a POST request to "/sessions" with values:
      | title       | Fixing IT for Experts           |
      | description | How to masquerade your failures |
    Then response code should be 201
    And there should be a persisted Session "Fixing IT for Experts" with values:
      | title       | Fixing IT for Experts           |
      | description | How to masquerade your failures |
