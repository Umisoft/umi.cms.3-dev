Feature: Registration
  In order to check registration form works correct
  As a website user
  I need to be able to register in the system and activate user after the registration

  Scenario: Try to register with confirmation as exist user
    Given I am on "/php"
    And I have "RU" language
    When I follow "Регистрация"
    And I fill in "login" with "minktest"
    And I fill in "Пароль" with "testpassword"
    And I fill in "Подтверждение пароля" with "testpassword"
    And I fill in "email" with "minktest@example.com"
    And I fill in "firstName" with "TestFirstName"
    And I fill in "middleName" with "TestMiddleName"
    And I fill in "lastName" with "TestLastName"
    And I press "Сохранить"
    Then I should see "Login is not unique"

  Scenario: Try to register with confirmation as non-exist user
    Given I am on "/php"
    And I have "RU" language
    And Activation is turned "on"
    When I follow "Регистрация"
    And I fill in "login" with "minktest2"
    And I fill in "password[]" with "testpassword"
    And I fill in "email" with "minktest2@example.com"
    And I fill in "firstName" with "TestFirstName"
    And I fill in "middleName" with "TestMiddleName"
    And I fill in "lastName" with "TestLastName"
    And I press "Сохранить"
    Then I should see "Письмо с ключом активации было выслано на Ваш электронный адрес"

  Scenario: Try to register with confirmation as non-exist user
    Given I am on "/php"
    And I have "RU" language
    And Activation is turned "off"
    When I follow "Регистрация"
    And I fill in "login" with "minktest3"
    And I fill in "password[]" with "testpassword"
    And I fill in "email" with "minktest3@example.com"
    And I fill in "firstName" with "TestFirstName"
    And I fill in "middleName" with "TestMiddleName"
    And I fill in "lastName" with "TestLastName"
    And I press "Сохранить"
    Then I should see "Вы успешно зарегистрировались и были авторизованы"

