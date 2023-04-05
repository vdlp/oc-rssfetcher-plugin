<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;
use Vdlp\RssFetcher\Models\Item;

final class PaginatableItems extends ComponentBase
{
    public ?Paginator $items = null;

    public function componentDetails(): array
    {
        return [
            'name' => 'vdlp.rssfetcher::lang.component.paginatable_item_list.name',
            'description' => 'vdlp.rssfetcher::lang.component.paginatable_item_list.description',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'itemsPerPage' => [
                'title' => 'vdlp.rssfetcher::lang.item.items_per_page',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'vdlp.rssfetcher::lang.item.items_per_page_validation',
                'default' => '10',
            ],
        ];
    }

    public function onRun(): void
    {
        $this->items = $this->loadItems();
    }

    private function loadItems(): Paginator
    {
        try {
            $items = Item::query()
                ->select(['vdlp_rssfetcher_items.*', 'vdlp_rssfetcher_sources.name AS source'])
                ->join(
                    'vdlp_rssfetcher_sources',
                    'vdlp_rssfetcher_items.source_id',
                    '=',
                    'vdlp_rssfetcher_sources.id'
                )
                ->where('vdlp_rssfetcher_sources.is_enabled', '=', 1)
                ->where('vdlp_rssfetcher_items.is_published', '=', 1)
                ->orderBy('vdlp_rssfetcher_items.pub_date', 'desc')
                ->paginate($this->property('itemsPerPage'));
        } catch (Throwable) {
            $items = new LengthAwarePaginator([], 0, $this->property('itemsPerPage'));
        }

        return $items;
    }
}
