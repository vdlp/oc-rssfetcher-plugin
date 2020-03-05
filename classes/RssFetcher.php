<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Classes;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Laminas\Feed\Reader\Entry\Rss;
use Laminas\Feed\Reader\Reader;
use October\Rain\Support\Traits\Singleton;
use Psr\Log\LoggerInterface;
use Throwable;
use Vdlp\RssFetcher\Models\Item;
use Vdlp\RssFetcher\Models\Source;

/**
 * Class RssFetcher
 *
 * @package Vdlp\RssFetcher\Classes
 */
final class RssFetcher
{
    use Singleton;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * {@inheritDoc}
     */
    protected function init(): void
    {
        $this->log = resolve(LoggerInterface::class);
    }

    /**
     * Run the fetching logic.
     *
     * @param int|null $sourceId
     */
    public function fetch(int $sourceId = null): void
    {
        $sources = $this->getSourceCollection($sourceId);
        $sources->each(function (Source $source) {
            try {
                $this->fetchSource($source);
            } catch (Throwable $e) {
                $this->log->error($e);
            }
        });
    }

    /**
     * @param Source $source
     * @throws Exception
     */
    private function fetchSource(Source $source): void
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

            try {
                Item::query()->updateOrCreate(
                    [
                        'source_id' => $source->getAttribute('id'),
                        'item_id' => $item->getId(),
                    ],
                    $attributes
                );
            } catch (Throwable $e) {
                $this->log->error($e);
            }

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
