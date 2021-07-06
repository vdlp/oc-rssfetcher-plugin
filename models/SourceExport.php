<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use Backend\Models\ExportModel;

class SourceExport extends ExportModel
{
    public $table = 'vdlp_rssfetcher_sources';

    public function exportData($columns, $sessionKey = null): array
    {
        return self::make()->query()->get()->toArray();
    }
}
