# 2.1.0

- Fix Author parsing for Atom/RSS feeds.

# 2.0.0

- BC: The content of an Item will not be stripped from HTML tags (see Events).
- Switch plugin dependencies from Zend Framework to Laminas.
- Added new events. Check the documentation on how to implement them.
    - `vdlp.rssfetcher.item.processTitle`
    - `vdlp.rssfetcher.item.processContent`
    - `vdlp.rssfetcher.item.processLink`
