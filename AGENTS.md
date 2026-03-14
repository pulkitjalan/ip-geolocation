# Repository Guidelines

- Keep changes surgical and limited to the issue being addressed.
- Use `composer test` for the existing automated test suite.
- This package supports Laravel 11.x, 12.x, and 13.x.
- The minimum supported PHP version is 8.2, with Laravel 13 requiring PHP 8.3+.
- In docblocks, always use fully qualified class names and do not add imports only for docblocks.
- In executable code, prefer normal `use` imports instead of fully qualified class names inline.
- Do not commit temporary files, dependencies, or build artifacts.
