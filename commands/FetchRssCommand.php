<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Vdlp\RssFetcher\Classes\RssFetcher;

final class FetchRssCommand extends Command
{
    public function __construct()
    {
        $this->name = 'vdlp:fetch-rss';
        $this->description = 'Fetches RSS data from various sources.';

        parent::__construct();
    }

    public function handle(): void
    {
        $sourceId = (int) $this->argument('source');

        RssFetcher::instance()->fetch($sourceId > 0 ? $sourceId : null);
    }

    protected function getArguments(): array
    {
        return [
            ['source', InputArgument::OPTIONAL, 'Source ID'],
        ];
    }
}
