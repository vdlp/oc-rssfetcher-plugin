<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Classes;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Collection;
use Laminas\Feed\Reader\Entry\EntryInterface;
use Laminas\Feed\Reader\Entry\Rss;
use Laminas\Feed\Reader\Feed\FeedInterface;
use Laminas\Feed\Reader\Reader;
use October\Rain\Support\Traits\Singleton;
use Psr\Log\LoggerInterface;
use stdClass;
use Throwable;
use Vdlp\RssFetcher\Models\Item;
use Vdlp\RssFetcher\Models\Source;

final class RssFetcher
{
    use Singleton;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    protected function init(): void
    {
        $this->log = resolve(LoggerInterface::class);
        $this->dispatcher = resolve(Dispatcher::class);
    }

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

    private function fetchSource(Source $source): void
    {
        $channel = Reader::import($source->getAttribute('source_url'));
        $maxItems = $source->getAttribute('max_items');

        $itemCount = 0;

        /** @var Rss $item */
        foreach ($channel as $item) {
            ++$itemCount;

            $dateCreated = $item->getDateCreated();

            $title = $item->getTitle();
            $this->dispatcher->fire('vdlp.rssfetcher.item.processTitle', [&$title]);

            $content = $item->getContent();
            $this->dispatcher->fire('vdlp.rssfetcher.item.processContent', [&$content]);

            $link = $item->getLink();
            $this->dispatcher->fire('vdlp.rssfetcher.item.processLink', [&$link]);

            $attributes = [
                'item_id' => $item->getId(),
                'source_id' => $source->getAttribute('id'),
                'title' => $title,
                'link' => $link,
                'description' => $content,
                'category' => implode(', ', $item->getCategories()->getValues()),
                'comments' => $item->getCommentLink(),
                'pub_date' => $dateCreated !== null ? $item->getDateCreated()->format('Y-m-d H:i:s') : null,
                'is_published' => (bool) $source->getAttribute('publish_new_items'),
                'author' => $this->getAuthor($channel, $item),
            ];

            $enclosure = $item->getEnclosure();

            if ($enclosure instanceof stdClass) {
                $attributes['enclosure_url'] = $enclosure->url ?? null;
                $attributes['enclosure_length'] = $enclosure->length ?? null;
                $attributes['enclosure_type'] = $enclosure->type ?? null;
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

    private function getAuthor(FeedInterface $feed, EntryInterface $entry): ?string
    {
        $result = null;
        $author = $entry->getAuthor();

        if ($author === null || empty($author)) {
            $author = $feed->getAuthor();
        }

        if (is_array($author)) {
            $result = $author['name'] ?? null;
        } elseif (is_string($author)) {
            $result = $author;
        }

        return $result;
    }

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
