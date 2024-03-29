<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation;

final class Feed extends Model
{
    use Validation;

    public $table = 'vdlp_rssfetcher_feeds';

    public $belongsToMany = [
        'sources' => [
            Source::class,
            'table' => 'vdlp_rssfetcher_feeds_sources',
            'order' => 'name',
        ],
    ];

    public array $rules = [
        'title' => 'required',
        'description' => 'required',
        'path' => [
            'required',
            'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i',
            'unique:vdlp_rssfetcher_feeds',
        ],
        'type' => 'required',
    ];
}
