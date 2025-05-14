# Article Model Tests

This directory contains focused test classes for the Article model functionality. The tests have been organized into logical groups to improve maintainability and readability.

## Test Classes

1. **ArticleCreationTest.php** - Tests for article creation and updating functionality
   - Creating articles with valid data
   - Attaching pictures to articles

2. **ArticleRetrievalTest.php** - Tests for retrieving articles functionality
   - Retrieving articles by user

3. **ArticleStatisticsTest.php** - Tests for article statistics functionality
   - Incrementing view counter

## Running the Tests

To run all Article model tests:

```bash
./vendor/bin/phpunit tests/Article
```

To run a specific test class:

```bash
./vendor/bin/phpunit tests/Article/ArticleCreationTest.php
```

## Test Coverage

These tests provide coverage of the basic Article model functionality. Each test class uses PHPUnit's mocking capabilities to isolate the Article model from the database, ensuring tests are fast and reliable.

## Potential Improvements

The current tests cover only the basic functionality. Consider adding tests for:

1. All public methods in the Articles class
   - getAll() - Retrieving all articles with different filters
   - getOne() - Retrieving a single article by ID
   - getSuggest() - Retrieving suggested articles

2. Edge cases and error conditions
   - Missing required fields
   - Database errors
   - Invalid input parameters
