Feature: Participant Controller
    In order to register
    as an AuthenticatedUser
    I want to see if the registration work as expected

    @fixtures
    Scenario: Creating a New Participant
        Given I am a user "foo" with password "bar" and role "Authenticated"
        When I send a POST request to "/participants" with values:
            | name       | Sascha Nowak           |
            | company    | netlogix GmbH & Co. KG |
            | street     | Neuwieder Straße 10    |
            | zip        | 90411                  |
            | city       | Nürnberg               |
            | country    | Deutschland            |
            | foodType   | default                |
            | tshirtSize | XL                     |
            | newcomer   | 0                      |
            | room       | 1                      |
            | roomSize   | 3                      |
        Then response code should be 200
