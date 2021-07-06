<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use October\Rain\Database\Builder;
use October\Rain\Database\Model;

class Item extends Model
{
    public $table = 'vdlp_rssfetcher_items';

    public $belongsTo = [
        'source' => Source::class
    ];

    protected $fillable = [
        'source_id',
        'item_id',
        'title',
        'link',
        'description',
        'author',
        'category',
        'comments',
        'enclosure_url',
        'enclosure_length',
        'enclosure_type',
        'pub_date',
        'is_published',
    ];

    protected $casts = [
        'enclosure_length' => 'integer'
    ];

    protected $dates = [
        'pub_date'
    ];

    /**
     * Allows filtering for specific sources
     *
     * @param Builder $query
     * @param array $sources List of source ids
     * @return Builder
     */
    public function scopeFilterSources(Builder $query, array $sources = []): Builder
    {
        return $query->whereHas('source', static function (Builder $q) use ($sources) {
            $q->whereIn('id', $sources);
        });
    }
}
