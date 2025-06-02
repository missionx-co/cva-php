# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

-   **Test**: `composer test` - Run all tests
-   **Test Single**: `vendor/bin/phpunit --filter TestName` - Run a specific test
-   **Coverage**: `composer test-coverage` - Run tests with coverage
-   **Format**: `composer format` - Format code using Laravel Pint

## Code Style Guidelines

-   **Namespace**: `MissionX\ClassVariantAuthority`
-   **PHP Version**: 8.3+
-   **Formatting**: Follow PSR-12, enforced by Laravel Pint
-   **Typing**: Use strict typing with proper docblocks for arrays/collections
-   **Error Handling**: Use exceptions with descriptive messages
-   **Method Chaining**: Preferred for builder-style APIs
-   **Dependencies**: Use TailwindMerge for class merging
-   **Testing**: Use PHPUnit with attributes (#[Test])
-   **Naming**: PascalCase for classes, camelCase for methods/properties
-   **Returns**: Use fluent interfaces (return $this) for builder methods
-   **Visibility**: Always declare property/method visibility (public/protected/private)
