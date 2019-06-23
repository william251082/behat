Feature: Authentication
  In order to gain access to the management area
  As an admin user
  I need to be able to login and logout

  Scenario: Logging in
    Given I am on "/"
    When I follow "Login"
    And I fill in "Username" with "admin"
    And I fill in "Password" with "admin"
    And I press "Login"
#    And print last response
    Then I should see "Logout"
