<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Components;

use Cms\Classes\ComponentBase;
use Throwable;
use Vdlp\RssFetcher\Models\Source;

final class Sources extends ComponentBase
{
    public array $sources = [];

    public function componentDetails(): array
    {
        return [
            'name' => 'vdlp.rssfetcher::lang.component.source_list.name',
            'description' => 'vdlp.rssfetcher::lang.component.source_list.description',
        ];
    }

    public function onRun(): void
    {
        $this->sources = $this->page['sources'] = self::loadSources();
    }

    public static function loadSources(): array
    {
        try {
            $sources = Source::query()
                ->where('is_enabled', '=', '1')
                ->orderBy('name');
        } catch (Throwable) {
            return [];
        }

        return $sources->get()
            ->toArray();
    }
}
