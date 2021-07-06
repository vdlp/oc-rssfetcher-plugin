# 3.0.0

- Drop support for PHP 7.4 (minimum required PHP version 7.4).

# 2.2.1

- Fix error when performing a migration rollback.

# 2.2.0

- Update plugin dependencies (minimum required PHP version 7.3).

# 2.1.0

- Fix Author parsing for Atom/RSS feeds.

# 2.0.0

- BC: The content of an Item will not be stripped from HTML tags (see Events).
- Switch plugin dependencies from Zend Framework to Laminas.
- Added new events. Check the documentation on how to implement them.
    - `vdlp.rssfetcher.item.processTitle`
    - `vdlp.rssfetcher.item.processContent`
    - `vdlp.rssfetcher.item.processLink`
