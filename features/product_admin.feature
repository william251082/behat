Feature: Product admin area
  In order to maintain the products shown on the site
  As an admin user
  I need to be able to add/edit/delete products

  Scenario: List available products
    Given There are 5 products
    And I am on "/admin"
    When I click "Products"
    Then I should see 5 products

  Scenario: Add a new product
    Given I am  on "/admin/products"
    When I click "Products"
    And I fill in "Name" with "velociraptorToy"
    And I fill in "Price" with "20"
    And I fill in "description" with "Have your raptor chew on this instead"
    And I press "save"
    Then I should see "product created"
