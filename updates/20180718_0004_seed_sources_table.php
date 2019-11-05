<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use Vdlp\RssFetcher\Models\Source;
use October\Rain\Database\Updates\Seeder;

/** @noinspection AutoloadingIssuesInspection */

/**
 * Class SeedAllTables
 *
 * @package Vdlp\RssFetcher\Updates
 */
class SeedAllTables extends Seeder
{
    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        Source::create([
            'name' => 'NU.nl | Algemeen nieuws',
            'description' => 'NU.nl | Algemeen nieuws',
            'source_url' => 'http://www.nu.nl/rss/Algemeen',
            'max_items' => 10,
            'is_enabled' => true,
        ]);
        Source::create([
            'name' => 'NU.nl | Internet',
            'description' => 'NU.nl | Internet',
            'source_url' => 'http://www.nu.nl/rss/Internet',
            'max_items' => 10,
            'is_enabled' => true,
        ]);
        Source::create([
            'name' => 'Tweakers.net',
            'description' => 'Tweakers.net is sinds 1998 de grootste website in Nederland over technologie en elektronica met nieuws, reviews en de bekroonde Pricewatch.',
            'source_url' => 'http://feeds.feedburner.com/tweakers/mixed',
            'max_items' => 10,
            'is_enabled' => true,
        ]);
        Source::create([
            'name' => 'Laravel News Blog',
            'description' => 'Laravel News Blog',
            'source_url' => 'http://feed.laravel-news.com/',
            'max_items' => 10,
            'is_enabled' => true,
        ]);
    }
}
