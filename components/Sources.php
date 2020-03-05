<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace Vdlp\RssFetcher\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Support\Collection;
use Throwable;
use Vdlp\RssFetcher\Models\Source;

/**
 * Class Sources
 *
 * @package Vdlp\RssFetcher\Components
 */
class Sources extends ComponentBase
{
    /**
     * @var Collection
     */
    public $sources;

    /**
     * {@inheritDoc}
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'vdlp.rssfetcher::lang.component.source_list.name',
            'description' => 'vdlp.rssfetcher::lang.component.source_list.description',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function onRun(): void
    {
        $this->sources = $this->page['sources'] = self::loadSources();
    }

    /**
     * Load Sources
     *
     * @return array
     */
    public static function loadSources(): array
    {
        try {
            $sources = Source::query()
                ->where('is_enabled', '=', '1')
                ->orderBy('name');
        } catch (Throwable $e) {
            return [];
        }

        return $sources->get()->toArray();
    }
}
