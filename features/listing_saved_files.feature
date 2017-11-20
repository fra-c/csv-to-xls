Feature: Listing saved files

  Scenario: List all saved files
    Given I saved a file with content "header1,header2\nfield1,field2"
    And I saved a file with content "some header1\nsome field"
    When I request a list of saved files
    Then I should get 2 entries
