<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Commands;

use Vdlp\RssFetcher\Classes\RssFetcher;
use Exception;
use Illuminate\Console\Command;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class FetchRssCommand
 *
 * @package Vdlp\RssFetcher\Commands
 */
class FetchRssCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'vdlp:fetch-rss';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Fetches RSS data from various sources.';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws RuntimeException
     * @throws Exception
     */
    public function handle(): void
    {
        $sourceId = (int) $this->argument('source');

        RssFetcher::instance()->fetch($sourceId > 0 ? $sourceId : null);
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * {@inheritdoc}
     */
    protected function getArguments(): array
    {
        return [
            ['source', InputArgument::OPTIONAL, 'Source ID'],
        ];
    }
}
