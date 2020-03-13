<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation;

class Source extends Model
{
    use Validation;

    /**
     * {@inheritDoc}
     */
    public $table = 'vdlp_rssfetcher_sources';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'fetched_at',
    ];

    /**
     * @var array
     */
    public $rules = [
        'name' => 'required',
        'source_url' => 'required',
    ];

    /**
     * {@inheritDoc}
     */
    public $hasMany = [
        'items' => [
            Item::class,
        ],
        'items_count' => [
            Item::class,
            'count' => true,
        ]
    ];
}
