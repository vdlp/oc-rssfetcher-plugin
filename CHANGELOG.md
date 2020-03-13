# 2.0.0

- BC: The content of an Item will not be stripped from HTML tags (see Events).
- Switch plugin dependencies from Zend Framework to Laminas.
- Added new events. Check the documentation on how to implement them.
    - `vdlp.rssfetcher.item.processTitle`
    - `vdlp.rssfetcher.item.processContent`
    - `vdlp.rssfetcher.item.processLink`
