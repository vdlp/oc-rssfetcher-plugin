<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Updates;

use October\Rain\Database\Updates\Seeder;
use Vdlp\RssFetcher\Models\Source;

final class SeedAllTables extends Seeder
{
    public function run(): void
    {
        Source::create([
            'name' => 'Tweakers.net',
            'description' => 'Tweakers.net is sinds 1998 de grootste website in Nederland over technologie en elektronica met nieuws, reviews en de bekroonde Pricewatch.',
            'source_url' => 'https://feeds.feedburner.com/tweakers/mixed',
            'max_items' => 10,
            'is_enabled' => true,
        ]);

        Source::create([
            'name' => 'Laravel News Blog',
            'description' => 'Laravel News Blog',
            'source_url' => 'https://feed.laravel-news.com/',
            'max_items' => 10,
            'is_enabled' => true,
        ]);
    }
}
