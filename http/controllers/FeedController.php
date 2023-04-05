<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Laminas\Feed\Writer\Entry;
use Laminas\Feed\Writer\Exception\InvalidArgumentException;
use Laminas\Feed\Writer\Feed;
use October\Rain\Database\Relations\HasMany;
use Vdlp\RssFetcher\Models\Feed as FeedModel;
use Vdlp\RssFetcher\Models\Item;
use Vdlp\RssFetcher\Models\Source;

final class FeedController
{
    public function __construct(
        private UrlGenerator $urlGenerator,
        private ResponseFactory $responseFactory,
    ) {
    }

    public function all(string $path): Response
    {
        /** @var ?FeedModel $model */
        $model = FeedModel::query()
            ->where('path', '=', $path)
            ->first();

        if ($model === null) {
            return $this->responseFactory->make('Not Found', 404);
        }

        $feed = new Feed();
        $feed->setTitle($model->getAttribute('title'))
            ->setDescription($model->getAttribute('description'))
            ->setBaseUrl($this->urlGenerator->to('/'))
            ->setGenerator('OctoberCMS/Vdlp.RssFetcher')
            ->setId('Vdlp.RssFetcher.' . $model->getAttribute('id'))
            ->setLink($this->urlGenerator->to('/feeds/' . $path))
            ->setFeedLink($this->urlGenerator->to('/feeds/' . $path), $model->getAttribute('type'))
            ->setDateModified()
            ->addAuthor(['name' => 'October CMS']);

        /** @var Collection $sources */
        $sources = $model->getAttribute('sources');
        $ids = Arr::pluck($sources->toArray(), 'id');
        $items = [];

        Source::with(['items' => static function (HasMany $builder) use (&$items, $model): void {
            $items = $builder->where('is_published', '=', 1)
                ->whereDate('pub_date', '<=', date('Y-m-d'))
                ->orderBy('pub_date', 'desc')
                ->limit($model->getAttribute('max_items'))
                ->get();
        }])->whereIn('id', $ids)
            ->where('is_enabled', '=', 1)
            ->get();

        /** @var Item $item */
        foreach ($items as $item) {
            try {
                $entry = new Entry();

                $entry->setId((string) $item->getAttribute('id'))
                    ->setTitle($item->getAttribute('title'))
                    ->setDescription($item->getAttribute('description'))
                    ->setLink($item->getAttribute('link'))
                    ->setDateModified($item->getAttribute('pub_date'));

                $comments = $item->getAttribute('comments');

                if (!empty($comments)) {
                    $entry->setCommentLink($comments);
                }

                $category = $item->getAttribute('category');

                if (!empty($category)) {
                    $entry->addCategory(['term' => $category]);
                }

                $enclosureUrl = $item->getAttribute('enclosure_url');

                if (!empty($enclosureUrl)) {
                    $entry->setEnclosure([
                        'uri' => $enclosureUrl,
                        'type' => $item->getAttribute('enclosure_type'),
                        'length' => $item->getAttribute('enclosure_length'),
                    ]);
                }

                $feed->addEntry($entry);
            } catch (InvalidArgumentException) {
                continue;
            }
        }

        return $this->responseFactory->make(
            $feed->export($model->getAttribute('type')),
            200,
            [
                'Content-Type' => sprintf('application/%s+xml', $model->getAttribute('type')),
            ]
        );
    }
}
