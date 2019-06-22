Feature: ls
  In order to see the directory structure
  As a UNIX user
  I need to be able to list the current directory's contents

  Scenario: List 2 files in a directory
    Given there is a file named "john"
    And there is a file named "hammond"
    When I run "ls"
    Then I should see "john" in the output
    And I should see "hammond" in the output

