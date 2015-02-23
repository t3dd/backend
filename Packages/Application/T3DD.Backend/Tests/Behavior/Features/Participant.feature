Feature: Participant Controller
    In order to register
    as an AuthenticatedUser
    I want to see if the registration work as expected

    @fixtures
    Scenario: Creating a New Participant
        Given I am a user "foo" with password "bar" and role "Authenticated"
        And I set header "Content-Type" with value "application/json"
        And I set header "Accept" with value "application/json"
        When I send a POST request to "/api/participants" with values:
            | name       | Sascha Nowak           |
            | company    | netlogix GmbH & Co. KG |
            | street     | Neuwieder Straße 10    |
            | zip        | 90411                  |
            | city       | Nürnberg               |
            | country    | Deutschland            |
            | foodType   | default                |
            | tshirtSize | XL                     |
            | newcomer   | 1                      |
            | room       | 1                      |
            | roomSize   | 3                      |
        Then response code should be 201
        And the there should be a persisted Participant for user "foo" with
            | name       | Sascha Nowak           |
            | company    | netlogix GmbH & Co. KG |
            | street     | Neuwieder Straße 10    |
            | zip        | 90411                  |
            | city       | Nürnberg               |
            | country    | Deutschland            |
            | foodType   | default                |
            | tshirtSize | XL                     |
            | newcomer   | 1                      |
            | room       | 1                      |
            | roomSize   | 3                      |

    @fixtures
    Scenario: Creating a New Participant is not allowed for everybody
        When I send a POST request to "/api/participants" with values:
            | name       | Sascha Nowak           |
        Then response code should be 401
        And there should be no persisted Participant

    @fixtures
    Scenario: Creating a new Participant is not allowed for users which already are a participant
        Given I am a user "foo" with password "bar" and role "Participant"
        And user "foo" has a participant with values:
            | name       | Foo Bar    |
            | company    | acme corp  |
            | street     | 1st Avenue |
            | zip        | 12345      |
            | city       | Some city  |
            | country    | USA        |
            | tshirtSize | L          |
        And I set header "Content-Type" with value "application/json"
        And I set header "Accept" with value "application/json"
        When I send a POST request to "/api/participants" with values:
            | name       | Sascha Nowak           |
        Then response code should be 400
