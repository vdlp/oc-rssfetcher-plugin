<p align="center">
	<img height="60px" width="60px" src="https://plugins.vdlp.nl/octobercms/icons/Vdlp.RssFetcher.svg" >
	<h1 align="center">Vdlp.RssFetcher</h1>
</p>

<p align="center">
	<em>Fetches RSS/Atom feeds to put on your website. It can be automated using a cronjob or triggered manually.</em>
</p>

<p align="center">
	<img src="https://badgen.net/packagist/php/vdlp/oc-rssfetcher-plugin">
	<img src="https://badgen.net/packagist/license/vdlp/oc-rssfetcher-plugin">
	<img src="https://badgen.net/packagist/v/vdlp/oc-rssfetcher-plugin/latest">
	<img src="https://badgen.net/packagist/dt/vdlp/oc-rssfetcher-plugin">
	<img src="https://badgen.net/badge/cms/October%20CMS">
	<img src="https://badgen.net/badge/type/plugin">
</p>

## Installation

Install this plugin within October CMS. It's available on the October CMS Market Place.

## RSS & Atom feeds

The plugin uses the `laminas/laminas-feed` package to parse the RSS and/or Atom feeds. For more information on this package goto https://docs.laminas.dev/laminas-feed/

## Components

The plugin is configured with 4 example sources and has a few components which help you to display items and/or sources on your website.

### Items

Shows a list of most recent RSS items limited on the maximum number of items provided by you.

An example of implementation code in your CMS page:

````
title = "A list of items"
url = "/items"
layout = "default"
is_hidden = 0

[rssItems]
maxItems = 10
==
{% component 'rssItems' %}
````

### PaginatableItems

Shows a list of most recent RSS items with an additional paginator element.

An example of implementation code in your CMS page:

````
title = "A list of items (paginatable)"
url = "/items"
layout = "default"
is_hidden = 0

[rssPaginatableItems]
itemsPerPage = 3
==
{% component 'rssPaginatableItems' %}
````

### Sources

Shows a list of sources.

An example of implementation code in your CMS page:

````
title = "A list of sources"
url = "/sources"
layout = "default"
is_hidden = 0

[rssSources]
==
{% component 'rssSources' %}
````

## Events

To manipulate RSS items there are a few events which can be used:
- `vdlp.rssfetcher.item.processTitle`
- `vdlp.rssfetcher.item.processContent`
- `vdlp.rssfetcher.item.processLink`

Use them like this:

```
Event::listen('vdlp.rssfetcher.item.processTitle', function (&$title) {
    $title = $title . 'A';
});

Event::listen('vdlp.rssfetcher.item.processContent', function (&$content) {
    $content = strip_tags($content);
});
```
## Reporting Widgets

This plugin contains also a **RSS Headlines** widget to show the latest headlines on your Dashboard. This widget has three configurable properties: `maxItems`, `title` and `dateFormat`.

## Cronjob

There are many ways to configure a cronjob. Here's an basic example of cronjob configuration line:

````
5/* * * * php path/to/artisan vdlp:fetch-rss >> /dev/null 2>&1
````

The above line takes care of fetching all sources every 5 minutes.

The `vdlp:fetch-rss` command takes an optional `source_id` argument. Provide the source ID if you want to fetch only 1 source at that time.

## Execute from code

In your plugin code you can also use the following code to execute the Artisan command:

````
<?php

use Artisan;
// ...

Artisan::call('vdlp:fetch-rss', ['source' => 2]);
````

Or using the `RssFetcher` singleton:

````
RssFetcher::instance()->fetch(2);
````

## Issues

If you have issues using this plugin. Please create an issue on GitHub or contact us at [octobercms@vdlp.nl]().

## Contribution

Any help is appreciated. Or feel free to create a Pull Request on GitHub.
