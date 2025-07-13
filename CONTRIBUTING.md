# Contributing to druidweb/coming-soon

Thank you for your interest in the druidweb/coming-soon package!

## Important Note

This package was created specifically for **Jetstream Labs projects** and contains company-specific branding and functionality. While we appreciate community interest, **we do not actively seek or expect external contributions** as this package serves our internal needs and brand requirements.

If you have suggestions or find issues, feel free to open an issue for discussion, but please understand that we may not accept pull requests that don't align with our specific use case.

## For Internal Contributors

If you're part of the Jetstream Labs team or working on approved projects, here's how to contribute:

### Development Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/druidweb/coming-soon.git
   cd coming-soon
   ```

2. **Install dependencies**

   ```bash
   composer install
   ```

3. **Run tests to ensure everything works**
   ```bash
   composer test
   ```

### Code Standards

We maintain high code quality standards:

- **PHP 8.4+** with strict types (`declare(strict_types=1);`)
- **100% test coverage** is required
- **PHPStan level max** with no errors
- **PSR-12** coding standards

### Testing Requirements

All code changes must include tests and maintain 100% coverage:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test:feat:coverage

# Run type checking
composer test:types

# Run code style checks
composer test:lint

# Fix reported issues from tests
composer fix
```

### Making Changes

1. **Create a feature branch**

   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**

   - Add tests for new functionality
   - Ensure all existing tests pass
   - Maintain 100% test coverage
   - Follow existing code patterns

3. **Run the full test suite**

   ```bash
   composer test
   composer test:feat:coverage
   composer test:types
   composer test:lint
   ```

4. **Commit your changes**

   ```bash
   git add .
   git commit -m "feat: add your feature description"
   ```

5. **Push and create a pull request**
   ```bash
   git push origin feature/your-feature-name
   ```

### Code Style

- Use strict types in all PHP files
- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Add proper PHPDoc comments
- Use the `#[Override]` attribute where applicable

### Testing Guidelines

- Write tests for all new functionality
- Test both success and failure scenarios
- Mock external dependencies appropriately
- Use descriptive test names that explain what is being tested
- Maintain 100% code coverage (no exceptions)

### Pull Request Process

1. Ensure all tests pass and coverage is 100%
2. Update documentation if needed
3. Describe your changes clearly in the PR description
4. Reference any related issues
5. Be prepared to make changes based on code review feedback

## Questions or Issues?

For internal team members, reach out through our usual communication channels.

For external users, you can open an issue, but please understand our limited scope for external contributions.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
