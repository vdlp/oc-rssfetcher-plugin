<?php

declare(strict_types=1);

use Illuminate\Routing\ResponseFactory;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use October\Rain\Database\Relations\HasMany;
use Vdlp\RssFetcher\Models\Feed as FeedModel;
use Vdlp\RssFetcher\Models\Item;
use Vdlp\RssFetcher\Models\Source;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Zend\Feed\Exception\InvalidArgumentException;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed;

/** @var Router $router */
$router = resolve(Router::class);
$router->get('/feeds/{path}', function ($path) {
    /** @var ResponseFactory $responseFactory */
    $responseFactory = resolve(ResponseFactory::class);

    /** @var UrlGenerator $urlGenerator */
    $urlGenerator = resolve(UrlGenerator::class);

    /** @var FeedModel $model */
    $model = FeedModel::query()->where('path', '=', $path)->first();

    if ($model === null) {
        return $responseFactory->make('Not Found', 404);
    }

    $feed = new Feed();
    $feed->setTitle($model->getAttribute('title'))
        ->setDescription($model->getAttribute('description'))
        ->setBaseUrl($urlGenerator->to('/'))
        ->setGenerator('OctoberCMS/Vdlp.RssFetcher')
        ->setId('Vdlp.RssFetcher.' . $model->getAttribute('id'))
        ->setLink($urlGenerator->to('/feeds/' . $path))
        ->setFeedLink($urlGenerator->to('/feeds/' . $path), $model->getAttribute('type'))
        ->setDateModified()
        ->addAuthor(['name' => 'October CMS']);

    /** @var Collection $sources */
    $sources = $model->getAttribute('sources');
    $ids = Arr::pluck($sources->toArray(), 'id');
    $items = [];

    Source::with(['items' => function (HasMany $builder) use (&$items, $model) {
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
        } catch (InvalidArgumentException $e) {
            continue;
        }
    }

    return $responseFactory->make($feed->export($model->getAttribute('type')), 200, [
        'Content-Type' => sprintf('application/%s+xml', $model->getAttribute('type')),
    ]);
});
