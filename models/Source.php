<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Source Model
 */
class Source extends Model
{
    use Validation;

    /**
     * {@inheritdoc}
     */
    public $table = 'vdlp_rssfetcher_sources';

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'fetched_at'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public $rules = [
        'name' => 'required',
        'source_url' => 'required',
    ];

    /**
     * {@inheritdoc}
     */
    public $hasMany = [
        'items' => [
            Item::class,
        ],
        'items_count' => [
            Item::class,
            'count' => true
        ]
    ];
}
