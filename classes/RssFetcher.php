<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Classes;

use Vdlp\RssFetcher\Models\Item;
use Vdlp\RssFetcher\Models\Source;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Log;
use October\Rain\Support\Traits\Singleton;
use Zend\Feed\Reader\Entry\Rss;
use Zend\Feed\Reader\Reader;

/**
 * Class RssFetcher
 *
 * @package Vdlp\RssFetcher\Classes
 */
class RssFetcher
{
    use Singleton;

    /**
     * Run the fetching logic.
     *
     * @param int|null $sourceId
     */
    public function fetch(int $sourceId = null)
    {
        $sources = $this->getSourceCollection($sourceId);
        $sources->each(function (Source $source) {
            try {
                $this->fetchSource($source);
            } catch (Exception $e) {
                Log::error($e);
            }
        });
    }

    /**
     * @param Source $source
     */
    private function fetchSource(Source $source)
    {
        $channel = Reader::import($source->getAttribute('source_url'));
        $maxItems = $source->getAttribute('max_items');

        $itemCount = 0;

        /** @var Rss $item */
        foreach ($channel as $item) {
            ++$itemCount;

            $dateCreated = $item->getDateCreated();

            $attributes = [
                'item_id' => $item->getId(),
                'source_id' => $source->getAttribute('id'),
                'title' => $item->getTitle(),
                'link' => $item->getLink(),
                'description' => strip_tags($item->getContent()),
                'category' => implode(', ', $item->getCategories()->getValues()),
                'comments' => $item->getCommentLink(),
                'pub_date' => $dateCreated !== null ? $item->getDateCreated()->format('Y-m-d H:i:s') : null,
                'is_published' => (bool) $source->getAttribute('publish_new_items')
            ];

            $enclosure = $item->getEnclosure();

            if ($enclosure instanceof \stdClass) {
                $attributes['enclosure_url'] = $enclosure->url ?? null;
                $attributes['enclosure_length'] = $enclosure->length ?? null;
                $attributes['enclosure_type'] = $enclosure->type ?? null;
            }

            if ($item->getAuthors() !== null && is_array($item->getAuthors())) {
                $attributes['author'] = implode(', ', $item->getAuthors());
            }

            Item::query()->firstOrCreate($attributes);

            if ($maxItems > 0 && $itemCount >= $maxItems) {
                break;
            }
        }

        $source->setAttribute('fetched_at', Carbon::now());
        $source->save();
    }

    /**
     * @param int|null $sourceId
     * @return Collection
     */
    private function getSourceCollection(int $sourceId = null): Collection
    {
        $sources = new Collection();

        if ($sourceId !== null) {
            $source = Source::query()
                ->where('id', '=', $sourceId)
                ->where('is_enabled', '=', true)
                ->first();

            if ($source) {
                $sources = new Collection([$source]);
            }
        } else {
            $sources = Source::query()
                ->where('is_enabled', '=', true)
                ->get();
        }

        return $sources;
    }
}
