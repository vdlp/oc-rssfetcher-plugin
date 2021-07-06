<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation;

final class Source extends Model
{
    use Validation;

    public $table = 'vdlp_rssfetcher_sources';

    public array $rules = [
        'name' => 'required',
        'source_url' => 'required',
    ];

    public $hasMany = [
        'items' => [
            Item::class,
        ],
        'items_count' => [
            Item::class,
            'count' => true,
        ],
    ];

    protected $dates = [
        'fetched_at',
    ];
}
