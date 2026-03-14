# Agent Guidelines

This document describes the conventions and guidelines for contributing to this repository.

## Requirements

- **PHP**: `>=8.2`
- **Laravel**: `^11.0 | ^12.0 | ^13.0`
  - Laravel 11 and 12 require PHP 8.2+
  - Laravel 13 requires PHP 8.3+

## Code Style

Code style is enforced with [Laravel Pint](https://github.com/laravel/pint) using the `laravel` preset. Imports are sorted by length. The `fully_qualified_strict_types` rule has `phpdoc_tags` set to `[]` so it only affects PHP type declarations, never docblocks. Run the linter with:

```bash
vendor/bin/pint
```

The CI workflow (`.github/workflows/php-cs-fixer.yml`) runs Pint automatically and commits any fixes.

### Imports and Docblocks

- **Docblocks must use fully qualified class names (FQCNs)** — e.g. `@return \PulkitJalan\IPGeolocation\IPGeolocation`, not `@return IPGeolocation`.
- **`use` statements must only be added for actual code usage** — never add an import solely for a docblock reference. Use the FQCN directly in the docblock instead.

```php
// Good
use PulkitJalan\IPGeolocation\IPGeolocation;

/**
 * @return \PulkitJalan\IPGeolocation\IPGeolocation
 */
public function getGeolocation(): IPGeolocation
{
    return $this->geolocation;
}

// Bad — import added only for docblock
use PulkitJalan\IPGeolocation\IPGeolocation;

/**
 * @return IPGeolocation
 */
public function getGeolocation(): IPGeolocation
{
    return $this->geolocation;
}
```

## Testing

Tests are written with [Pest](https://pestphp.com/) (`^3.8`). Run the test suite with:

```bash
composer test
# or directly:
vendor/bin/pest
```

Run with coverage (requires Xdebug):

```bash
vendor/bin/pest --coverage
```

## Dependencies

### Runtime

- [`guzzlehttp/guzzle`](https://github.com/guzzle/guzzle): `^7.8`
- [`geoip2/geoip2`](https://github.com/maxmind/GeoIP2-php): `^3.0`

### Development

- [`mockery/mockery`](https://github.com/mockery/mockery): `^1.6` (dev)
- [`pestphp/pest`](https://pestphp.com/): `^3.8` (dev)

## CI

The GitHub Actions workflow (`.github/workflows/run-tests.yml`) tests against:

| PHP | Laravel        |
|-----|----------------|
| 8.2 | 11, 12         |
| 8.3 | 11, 12, 13     |
| 8.4 | 11, 12, 13     |

Both `prefer-lowest` and `prefer-stable` dependency versions are tested. Coverage is uploaded to Codecov for the PHP 8.3 / Laravel 11 / prefer-stable combination.

## General

- Keep changes surgical and limited to the issue being addressed.
- Do not commit temporary files, dependencies, or build artifacts.
