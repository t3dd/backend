Feature: Create sessions
  In order to create sessions
  As a authenticated user
  I want to submit sessions

  @fixtures
  Scenario: Create a new session
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
    Given I am a user "foo" with password "bar" and role "Authenticated"
    And I set header "Content-Type" with value "application/json"
    And I set header "Accept" with value "application/json"
    When I send a POST request to "/sessions" with values:
      | title          | Fixing IT for Experts                |
      | description    | How to masquerade your failures      |
      | theme          | 566e1778-6984-e8b1-6938-921a6b62b12b |
      | type           | 868351dc-cdad-850c-b4c3-eff2ffe6976f |
      | expertiseLevel | d9a3d833-d53e-f968-6ba4-4706ca1ef355 |
    Then response code should be 201
    And there should be a persisted Session "Fixing IT for Experts" with values:
      | title          | Fixing IT for Experts                |
      | description    | How to masquerade your failures      |
      | theme          | TYPO3 Neos |
      | type           | Presentation |
      | expertiseLevel | Expert |

  @fixtures
  Scenario: Create a new session and test validation
    Given I am a user "foo" with password "bar" and role "Authenticated"
    And I set header "Content-Type" with value "application/json"
    And I set header "Accept" with value "application/json"
    When I send a POST request to "/sessions" with values:
      | title | Fixing IT for Experts |
    Then response code should be 400
    And the response should contain the json subset:
      """
      {
        "errors": {
          "session.description": [
            {
              "code": 1221560910,
              "message": "This property is required."
            }
          ],
          "session.theme": [
            {
              "code": 1221560910,
              "message": "This property is required."
            }
          ],
          "session.type": [
            {
              "code": 1221560910,
              "message": "This property is required."
            }
          ],
          "session.expertiseLevel": [
            {
              "code": 1221560910,
              "message": "This property is required."
            }
          ]
        },
        "success": false
      }
      """