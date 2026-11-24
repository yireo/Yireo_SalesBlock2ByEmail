# Yireo SalesBlock2ByEmail for Magento 2

<!-- badges.specs.start -->
![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/magento2-salesblock2-by-email)
<!-- badges.specs.end -->

This module is a helper-module for the [Yireo_SalesBlock2](https://www.yireo.com/software/magento-extensions/salesblock2) extension, that allows you to block orders from being placed, based on specific rules defined in the Magento Admin Panel.

This specific module allows you to match by email address. Each line (or comma, semicolon or pipe separated value) of a condition is a pattern:

| Pattern | Matches |
|---|---|
| `john@example.org` | exactly this address |
| `@example.org` or `example.org` | every address at `example.org` and its subdomains (like `mail.example.org`), but not `myexample.org` |
| `spam*@*` or `*@example.*` | a wildcard match against the full address (`*` is the only wildcard, at most 3 per pattern) |

Before matching, both the email address and the pattern are normalized: whitespace is trimmed, everything is lowercased, a trailing dot of the domain is removed and international domains are converted to punycode. Sub-addresses (`john+shop@example.org`) are matched as `john@example.org` and dots in Gmail addresses are ignored (`j.o.hn@googlemail.com` is matched as `john@gmail.com`).

Patterns are validated when saving a rule: regular expression characters (like `^`, `$`, `(` or `?`), HTML characters, multiple `@` and patterns matching everything (like `*@*`) are rejected.

**Upgrading from 2.x**: Older versions matched every pattern as a case-insensitive substring (`example` matched `info@example.org`). Since 3.0, a partial address or a partial domain no longer matches. Review your existing rules after upgrading.

### Installation
To install this module, use the following commands. First, install this module using composer. Note that this step will fail if the `Yireo_SalesBlock2` is not installed yet.
 
    composer require yireo/magento2-salesblock2-by-email
    
Once this module is installed via composer, you can enable it:

    ./bin/magento module:enable Yireo_SalesBlock2ByEmail

There are no further steps to take. The `Yireo_SalesBlock2` module automatically picks up on things.
## Current status

<!-- badges.test.start -->
![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByEmail/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByEmail/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByEmail/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByEmail/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByEmail/compile.yml?label=compile)
<!-- badges.test.end -->
