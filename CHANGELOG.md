# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [4.0.0] - 2025-08-14

### Added

- Add support for October CMS 4.x.

### Fixes

- Allow `/` within a valid feed path e.g. `example/feed/path`.

## [3.3.0] - 2024-05-06

### Added

- Added Chinese translations.

## [3.2.0] - 2023-04-05

- Dropped support for October CMS 2.0
- Fixed error in Sources controller.
- Fixed error in Headlines report widget.
- Improve UI for Feed Path form field.

## [3.1.0] - 2022-09-23

### Added

- Added missing translation for 'access_feeds' (Manage feeds).

### Removed

- Dropped support for PHP 7.4
- Dropped support for October CMS 1.0

## [3.0.4] - 2021-09-10

### Fixes

- Version 3.0.0 description contains a typo in PHP version. Correct description: "Dropped support for PHP 7.3".

### Added

- Missing versions from CHANGELOG.

## [3.0.3] - 2021-07-07

### Changed

- Replace the use of Event facade with Event dispatcher.

## [3.0.2] - 2021-07-07

### Fixes

- Fix type in Items component (Items::$items)

## [3.0.1] - 2021-07-07

### Fixes

- Fix type in Sources component (Sources::$sources)

## [3.0.0] - 2021-07-06

### Removed

- Drop support for PHP 7.3 (minimum required PHP version 7.4).

## [2.2.1] - 2021-07-06

### Fixes

- Fix error when performing a migration rollback.

## [2.2.0] - 2021-05-28

- Update plugin dependencies (minimum required PHP version 7.3).

## [2.1.0] - 2021-03-26

- Fix Author parsing for Atom/RSS feeds.

## [2.0.0] - 2021-03-13

- BC: The content of an Item will not be stripped from HTML tags (see Events).
- Switch plugin dependencies from Zend Framework to Laminas.
- Added new events. Check the documentation on how to implement them.
    - `vdlp.rssfetcher.item.processTitle`
    - `vdlp.rssfetcher.item.processContent`
    - `vdlp.rssfetcher.item.processLink`
