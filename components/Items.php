<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace Vdlp\RssFetcher\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Support\Collection;
use Throwable;
use Vdlp\RssFetcher\Models\Item;

/**
 * Class Items
 *
 * @package Vdlp\RssFetcher\Components
 */
class Items extends ComponentBase
{
    /**
     * @var Collection
     */
    public $items;

    /**
     * {@inheritDoc}
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'vdlp.rssfetcher::lang.component.item_list.name',
            'description' => 'vdlp.rssfetcher::lang.component.item_list.description',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function defineProperties(): array
    {
        return [
            'maxItems' => [
                'label' => 'vdlp.rssfetcher::lang.item.max_items',
                'type' => 'string',
                'default' => '10',
            ],
            'sourceId' => [
                'label' => 'vdlp.rssfetcher::lang.item.source_id',
                'type' => 'string',
                'default' => '',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function onRun(): void
    {
        $sourceId = (int) $this->property('sourceId');

        $this->items = self::loadItems(
            (int) $this->property('maxItems', 10),
            $sourceId > 0 ? $sourceId : null
        );
    }

    /**
     * Load Items
     *
     * @param int $maxItems
     * @param int $sourceId
     * @return array
     */
    public static function loadItems(int $maxItems, int $sourceId = null): array
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
                ->limit($maxItems);

            if ($sourceId !== null && is_numeric($sourceId)) {
                $items->where('vdlp_rssfetcher_items.source_id', '=', (int) $sourceId);
            }
        } catch (Throwable $e) {
            return [];
        }

        return $items->get()->toArray();
    }
}
