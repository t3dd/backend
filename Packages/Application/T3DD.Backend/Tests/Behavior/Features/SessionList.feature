Feature: List and show sessions
  In order to browse sessions
  As a user
  I want to retrieve sessions

  @fixtures
  Scenario: List sessions without authentication
    Given I have a Value with values
      | persistence_object_identifier | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | title                         | TYPO3 Neos                           |
      | type                          | theme                                |
    Given I have a Value with values
      | persistence_object_identifier | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | title                         | Presentation                         |
      | type                          | type                                 |
    Given I have a Value with values
      | persistence_object_identifier | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
      | title                         | Expert                               |
      | type                          | expertiseLevel                       |
    Given I have a Session with values
      | title          | Test Session for Dummies             |
      | description    | Lorem ipsum dolor sit amet           |
      | theme          | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | type           | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | expertiseLevel | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions"
    Then the response code should be 200
    And the response should contain the json subset:
      """
      [{
        "title": "Test Session for Dummies",
        "description": "Lorem ipsum dolor sit amet",
        "theme": "TYPO3 Neos",
        "type": "Presentation",
        "expertiseLevel": "Expert"
      }]
      """

  @fixtures
  Scenario: List sessions as authenticated user
    Given I have a Value with values
      | persistence_object_identifier | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | title                         | TYPO3 Neos                           |
      | type                          | theme                                |
    Given I have a Value with values
      | persistence_object_identifier | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | title                         | Presentation                         |
      | type                          | type                                 |
    Given I have a Value with values
      | persistence_object_identifier | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
      | title                         | Expert                               |
      | type                          | expertiseLevel                       |
    Given I have a Session with values
      | title          | Test Session for Dummies             |
      | description    | Lorem ipsum dolor sit amet           |
      | theme          | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | type           | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | expertiseLevel | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
    And I am a user "foo" with password "bar" and role "Authenticated"
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions"
    Then the response code should be 200
    And the response should contain the json subset:
      """
      [{
        "title": "Test Session for Dummies",
        "description": "Lorem ipsum dolor sit amet",
        "theme": "TYPO3 Neos",
        "type": "Presentation",
        "expertiseLevel": "Expert"
      }]
      """

  @fixtures
  Scenario: Retrieve a single session
    Given I have a Value with values
      | persistence_object_identifier | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | title                         | TYPO3 Neos                           |
      | type                          | theme                                |
    Given I have a Value with values
      | persistence_object_identifier | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | title                         | Presentation                         |
      | type                          | type                                 |
    Given I have a Value with values
      | persistence_object_identifier | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
      | title                         | Expert                               |
      | type                          | expertiseLevel                       |
    Given I have a Session with values
      | title          | Single Test Session                  |
      | description    | Lorem ipsum dolor sit amet           |
      | theme          | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | type           | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | expertiseLevel | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions/single-test-session"
    Then the response code should be 200
    And the response should contain json:
      """
      {
        "title": "Single Test Session",
        "description": "Lorem ipsum dolor sit amet",
        "theme": "TYPO3 Neos",
        "type": "Presentation",
        "expertiseLevel": "Expert"
      }
      """

  @fixtures
  Scenario: Retrieve session list with multiple sessions
    Given I have a Value with values
      | persistence_object_identifier | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | title                         | TYPO3 Neos                           |
      | type                          | theme                                |
    Given I have a Value with values
      | persistence_object_identifier | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | title                         | Presentation                         |
      | type                          | type                                 |
    Given I have a Value with values
      | persistence_object_identifier | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
      | title                         | Expert                               |
      | type                          | expertiseLevel                       |
    Given I have a Session with values
      | title          | Test Session for Dummies             |
      | description    | Lorem ipsum dolor sit amet           |
      | date           | 2015-01-01T12:00:00Z                 |
      | theme          | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | type           | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | expertiseLevel | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
    Given I have a Session with values
      | title          | Test Session for Experts             |
      | description    | Foo bar baz                          |
      | date           | 2015-01-02T12:00:00Z                 |
      | theme          | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | type           | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | expertiseLevel | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
    And I set header "Accept" with value "application/json"
    When I send a GET request to "/sessions"
    Then the response code should be 200
    And the response should contain the json subset:
      """
      [{
        "title": "Test Session for Experts",
        "description": "Foo bar baz",
        "theme": "TYPO3 Neos",
        "type": "Presentation",
        "expertiseLevel": "Expert"
      }, {
        "title": "Test Session for Dummies",
        "description": "Lorem ipsum dolor sit amet",
        "theme": "TYPO3 Neos",
        "type": "Presentation",
        "expertiseLevel": "Expert"
      }]
      """
