# Menu Consumer Library

A PHP library for interacting with the Great Food Ltd REST API (and potentially more in the future), providing functionality to retrieve menus, products, and update product information.

## Installation

```bash
composer install
```

## Usage

### Scenario 1: List Products for the Takeaway Menu

```bash
php examples/scenario1.php
```

This will authenticate with the API, retrieve the "Takeaway" menu, fetch its products, and display them in a tabular format.

### Scenario 2: Update Product Name

```bash
php examples/scenario2.php
```

This demonstrates updating a product name (e.g., correcting "Chpis" to "Chips" for product ID 84 in menu 7).

## Testing

Run the test suite:

```bash
composer test
```

The test suite includes:
- API endpoint integration tests
- Scenario 1 validation
- Scenario 2 validation

## Code Quality

### PHP-CS-Fixer

Check and fix code style issues:

```bash
composer cs-fixer
```

This will automatically fix code style issues according to the configured rules.

### PHPStan

Run static analysis to detect potential bugs:

```bash
composer phpstan
```

This will analyze the codebase for type errors, potential bugs, and other issues.

## Architecture

The library is structured with:
- **API Client** (`GreatFood`): Main interface for API operations
- **Authentication** (`GreatFoodAuthClient`): Handles OAuth token retrieval
- **HTTP Client** (`HttpClient`): Abstract HTTP interface (currently mocked for testing)
- **Models** (`Menu`, `Product`): Domain models for API responses

## Potential Concerns & Improvements

- **Real HTTP Client**: Currently only a `MockHttpClient` is implemented. For production use, a real HTTP client implementation (e.g., using Symfony HTTPClient) should be added.
- **Token Expiration**: Not implemented, makes sense to be added with the full HttpClient implementation.
- **Secret management**: Currently secrets are hardcoded in the scenario*.php files. These would be better loaded in as environment variables, especially in the real implementation and in production 
- **Error handling and retries**: Add retry with backoff for HTTP or network errors.
- **Response validation**: Validate and normalise API responses; surface clear success/exception messages, rather than tabulated responses/inconsistent strings.
- **Logging/observability**: Optional request/response logging for debugging.
- **Increasing code standards**: Rules can be added to php-cs-fixer and the level can be increased in PHPStan to target higher levels of conformity.

### Further considerations (considered after 2 hour limit)
- HTTP Client including mock should have been based on PSR-17 and PSR-18 for better drop-in replacement of different HTTP clients (or Symfony HTTPClient)
- Api\GreatFood should be based on a common interface so that the same methods e.g. getMenuByName is available for all API providers
- Same as above for classes in Model
- Separating mocked responses into Request/Response GET/POST/PUT directories improves understandability & limits developer confusion or conflict (e.g. GET and POST to same endpoint)
- The mock HTTP implementation doesn't actually verify that Authentication is supplied or if it is correct. On all endpoints that require auth we could add an explicit check against token.json.
