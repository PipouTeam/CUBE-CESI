# User Model Tests

This directory contains focused test classes for the User model functionality. The tests have been organized into logical groups to improve maintainability and readability.

## Test Classes

1. **UserCreationTest.php** - Tests for user creation functionality
   - Creating users with valid data
   - Validation of required fields
   - Email format validation
   - Handling duplicate emails
   - Creating users without photos (addressing known issue)

2. **UserLoginTest.php** - Tests for login functionality
   - Login with valid credentials
   - Login with invalid credentials
   - Password case sensitivity
   - Auto-login after registration (addressing known issue)
   - User retrieval by login

3. **RememberTokenTest.php** - Tests for "remember me" functionality
   - Setting remember tokens
   - Retrieving users by token
   - Handling expired tokens
   - Deleting tokens

## Running the Tests

To run all User model tests:

```bash
./vendor/bin/phpunit tests/User
```

To run a specific test class:

```bash
./vendor/bin/phpunit tests/User/UserCreationTest.php
```

## Test Coverage

These tests cover all the core functionality of the User model, including edge cases and the specific issues identified in the project:

1. "Remember me" button functionality
2. Users not auto-logged in after registration
3. Error when posting without photo

Each test class uses PHPUnit's mocking capabilities to isolate the User model from the database, ensuring tests are fast and reliable.
