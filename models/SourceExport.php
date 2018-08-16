<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use Backend\Models\ExportModel;

/**
 * Class SourceExport
 *
 * @package Vdlp\RssFetcher\Models
 */
class SourceExport extends ExportModel
{
    /**
     * {@inheritdoc}
     */
    public $table = 'vdlp_rssfetcher_sources';

    /**
     * {@inheritdoc}
     */
    public function exportData($columns, $sessionKey = null): array
    {
        return self::make()->query()->get()->toArray();
    }
}
