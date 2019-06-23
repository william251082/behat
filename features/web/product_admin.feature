Feature: Product admin area
  In order to maintain the products shown on the site
  As an admin user
  I need to be able to add/edit/delete products

  Scenario: List available products
    Given There are 5 products
    And I am logged in as an admin
    And I am on "/admin"
    When I click "Products"
    Then I should see 5 products

  Scenario: Products show author
    Given I am logged in as an admin
    And I author 5 products
    When I go to "/admin/products"
    Then I should not see "Anonymous"

  @javascript
  Scenario: Add a new product
    Given I am logged in as an admin
    And I am on "/admin/products"
    When I click "New Product"
    And I wait for the modal to load
    And break
    And I fill in "Name" with "velociraptorToy"
    And I fill in "Price" with "20"
    And I fill in "Description" with "Have your raptor chew on this instead"
    And I press "save"
    Then I should see "product created"
    And I should see "velociraptorToy"
    Then I should not see "Anonymous"

#    behatch context library
