#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Running all PHPUnit tests in the tests directory...${NC}"

# Check if vendor/bin/phpunit exists
if [ ! -f "vendor/bin/phpunit" ]; then
    echo -e "${RED}PHPUnit not found. Make sure you have installed dependencies with Composer.${NC}"
    exit 1
fi

# Get all test files recursively
TEST_FILES=$(find tests -name "*Test.php")

# Count total tests
TOTAL_TESTS=$(echo "$TEST_FILES" | wc -l)
PASSED_TESTS=0
FAILED_TESTS=0

echo -e "${YELLOW}Found $TOTAL_TESTS test files.${NC}"
echo ""

# Run each test file individually
for TEST_FILE in $TEST_FILES; do
    echo -e "${YELLOW}Running test: $TEST_FILE${NC}"
    
    # Run the test and capture output and exit code
    OUTPUT=$(vendor/bin/phpunit "$TEST_FILE" 2>&1)
    EXIT_CODE=$?
    
    # Display the output
    echo "$OUTPUT"
    
    # Check if the test passed or failed
    if [ $EXIT_CODE -eq 0 ]; then
        echo -e "${GREEN}✓ Test passed: $TEST_FILE${NC}"
        PASSED_TESTS=$((PASSED_TESTS + 1))
    else
        echo -e "${RED}✗ Test failed: $TEST_FILE${NC}"
        FAILED_TESTS=$((FAILED_TESTS + 1))
    fi
    
    echo ""
done

# Display summary
echo -e "${YELLOW}Test Summary:${NC}"
echo -e "${GREEN}Passed: $PASSED_TESTS${NC}"
echo -e "${RED}Failed: $FAILED_TESTS${NC}"
echo -e "${YELLOW}Total: $TOTAL_TESTS${NC}"

# Return appropriate exit code
if [ $FAILED_TESTS -gt 0 ]; then
    exit 1
else
    exit 0
fi
