Feature: Registration
  In order to check registration form works correct
  As a website user
  I need to be able to register in the system and activate user after the registration

  Scenario: Try to register without confirmation
    Given I am on "/php"
    And I have "RU" language
    When I follow "Регистрация"
    And I fill in "login" with "minktest"
    And I fill in "password" with "testpassword"
    And I fill in "email" with "minktest@example.com"
    And I fill in "firstName" with "TestFirstName"
    And I fill in "middleName" with "TestMiddleName"
    And I fill in "lastName" with "TestLastName"
    And I press "Сохранить"
    Then I should see "Login is not unique"

#  Scenario: Try to login with correct login or password
#    Given I am on "/php"
#    And I have "RU" language
#    When I fill in "login" with "minktest"
#    And I fill in "password" with "testpassword"
#    And I press "Log in"
#    Then I should see "Добро пожаловать, testuser"

