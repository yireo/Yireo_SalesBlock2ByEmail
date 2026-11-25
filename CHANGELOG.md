# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 25 November 2026
### Changed
- BREAKING: email rules now match explicitly instead of as case-insensitive substrings: a full address matches exactly, `@domain` or `domain` matches that domain and its subdomains, and `*` is a wildcard. Partial addresses or domains (like `example` or `info@example`) no longer match, so review existing rules
- Emails and patterns are normalized before matching (whitespace, case, trailing dot, IDN, +tags, Gmail dots)
- Patterns are validated when a rule is saved
- Requires yireo/magento2-salesblock2 ^2.3

### Fixed
- Guest orders with a blocked email address were not prevented, because the matcher never received the order email
- An empty email address no longer counts as a match
- The match message is now translatable
- Removed unused Magento_Quote dependency and declared the framework dependency

### Removed
- Dropped PHP 8.1 support; PHP 8.2 up to 8.5 is supported

## [2.2.1] - 9 April 2024
### Fixed
- Upgrade due to renamed PHP 8 class

### Removed
- Removed unneeded module version

## [2.2.0] - 16 April 2022
### Fixed
- PHP 8 compatibility (and therefore Magento 2.4.4)

## [2.1.0] - 18 February 2021
### Added
- Implemented new debugger

## [2.0.1] - 29 July 2020
### Added
- Magento 2.4 compatibility
- Remove PHP dep from composer

## [2.0.0] - July 2019
### Added
- Initial public release
