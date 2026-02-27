# Contributing to ITS FluentCRM Email Attachment

Thank you for your interest in contributing! We welcome bug reports, feature requests, and pull requests from the community.

## Code of Conduct

This project adheres to a [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to [info@itsgroup.co.nz](mailto:info@itsgroup.co.nz).

## How to Contribute

### Reporting Bugs

Before submitting a bug report, please check the [existing issues](https://github.com/itsgroup-co-nz/its-fluentcrm-email-attachment/issues) to avoid duplicates.

When filing a bug report, please include:

- A clear and descriptive title.
- Steps to reproduce the problem.
- The expected behaviour and what actually happened.
- Your environment details (WordPress version, PHP version, FluentCRM version, plugin version).
- Any relevant error messages or screenshots.

### Suggesting Features

Feature requests are welcome. Please open an issue describing:

- The problem you are trying to solve.
- Your proposed solution or the feature you would like to see.
- Any alternatives you have considered.

### Submitting a Pull Request

1. **Fork** the repository and create your branch from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**, keeping commits focused and atomic.

3. **Test your changes** against the requirements listed below.

4. **Submit a pull request** with a clear description of the problem and solution, referencing any related issues.

## Development Setup

### Requirements

- WordPress 5.6 or higher
- PHP 7.4 or higher
- FluentCRM plugin installed and activated

### Installation for Development

1. Clone the repository into your WordPress plugins directory:
   ```bash
   git clone https://github.com/itsgroup-co-nz/its-fluentcrm-email-attachment.git wp-content/plugins/its-fluentcrm-email-attachment
   ```

2. Activate the plugin through the **Plugins** menu in WordPress.

3. Ensure FluentCRM (Free or Pro version) is installed and activated.

## Coding Standards

- Follow the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).
- Keep changes focused on the issue being addressed.
- Do not introduce new dependencies without prior discussion.
- Write clear, descriptive commit messages.

## Pull Request Guidelines

- One pull request per feature or bug fix.
- Keep pull requests small and focused — large PRs are harder to review.
- Update the `README.md` if your change affects usage or installation.
- Ensure your code does not introduce PHP warnings or errors.

## Questions

If you have questions about contributing, feel free to open an issue or contact us at [info@itsgroup.co.nz](mailto:info@itsgroup.co.nz).
