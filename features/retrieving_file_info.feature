Feature: Retrieving file info

  Scenario: Retrieve ID and link
    Given I saved a file with content "header1,header2\nfield1,field2"
    When I request the file info
    Then I should get a link to the saved file containing the following data:
      | header1 | header2 |
      | field1  | field2  |
