# Utility Tests

This directory contains focused test classes for the Utility components. The tests have been organized into logical groups to improve maintainability and readability.

## Test Classes

1. **HashTest.php** - Tests for the Hash utility class
   - Hash generation with and without salt
   - Salt generation with various parameters
   - Error handling for invalid inputs

2. **FlashTest.php** - Tests for the Flash utility class
   - Storing flash messages in session
   - Retrieving flash messages from session
   - Proper cleanup of session data after retrieval

## Running the Tests

To run all Utility tests:

```bash
./vendor/bin/phpunit tests/Utility
```

To run a specific test class:

```bash
./vendor/bin/phpunit tests/Utility/HashTest.php
```

## Test Coverage

These tests provide coverage of the basic functionality of the Utility classes. Each test class follows PHPUnit best practices and includes proper setup and teardown methods where needed.

## Potential Improvements

The current tests cover the basic functionality. Consider adding tests for:

1. Additional Hash class methods
   - generateUnique() - Currently not used but might be implemented in the future

2. Additional Flash class methods
   - Add tests for success, info, and warning message types if implemented
   - Test flash message persistence across multiple requests

3. Edge cases and error conditions
   - Test with very large inputs
   - Test with special characters
   - Test with different character encodings
