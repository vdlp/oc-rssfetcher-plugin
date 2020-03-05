<?php

/* @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace Vdlp\RssFetcher\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Vdlp\RssFetcher\Classes\RssFetcher;

/**
 * Class FetchRssCommand
 *
 * @package Vdlp\RssFetcher\Commands
 */
class FetchRssCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected $name = 'vdlp:fetch-rss';

    /**
     * {@inheritDoc}
     */
    protected $description = 'Fetches RSS data from various sources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $sourceId = (int) $this->argument('source');

        RssFetcher::instance()->fetch($sourceId > 0 ? $sourceId : null);
    }

    /**
     * {@inheritDoc}
     */
    protected function getArguments(): array
    {
        return [
            ['source', InputArgument::OPTIONAL, 'Source ID'],
        ];
    }
}
