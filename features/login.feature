Feature: Login
  In order to check login form works correct
  As a website user
  I need to be able to login to the system

  Scenario: Try to login with incorrect login or password
    Given I am on "/php"
    And I have "RU" language
    When I fill in "login" with "222"
    And I fill in "password" with "333"
    And I press "Log in"
    Then I should see "Неверный логин или пароль"

  Scenario: Try to login with correct login or password
    Given I am on "/php"
    And I have "RU" language
    When I fill in "login" with "minktest"
    And I fill in "password" with "testpassword"
    And I press "Log in"
    Then I should see "Добро пожаловать, testuser"

