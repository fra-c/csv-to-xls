Feature: Saving files

  Scenario: Save file
    When I save a file with content "header1,header2\nfield1,field2"
    Then an ID for the resource should be created
    And a log entry should be saved
