Feature: Vote for sessions
  In order to have votes for sessions
  As a user
  I want to submit a vote

  @fixtures
  Scenario: Submit a vote
    Given I have a Value with values
      | persistence_object_identifier | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | title                         | TYPO3 Neos                           |
      | type                          | theme                                |
    And I have a Value with values
      | persistence_object_identifier | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | title                         | Presentation                         |
      | type                          | type                                 |
    And I have a Value with values
      | persistence_object_identifier | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
      | title                         | Expert                               |
      | type                          | expertiseLevel                       |
    And I have a Session with values
      | title          | Test Session for Dummies             |
      | description    | Lorem ipsum dolor sit amet           |
      | type           | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | expertiseLevel | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
    And I am a user "foo" with password "bar" and role "Authenticated"
    And I set header "Accept" with value "application/json"
    When I send a POST request to "/vote/test-session-for-dummies"
    Then the response code should be 201

