<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use Backend\Models\ImportModel;
use Throwable;

/**
 * Class SourceImport
 *
 * @package Vdlp\RssFetcher\Models
 */
class SourceImport extends ImportModel
{
    /**
     * {@inheritDoc}
     */
    public $table = 'vdlp_rssfetcher_sources';

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
    public function importData($results, $sessionKey = null)
    {
        foreach ((array) $results as $row => $data) {
            try {
                /** @var Source $source */
                $source = Source::make();

                $except = ['id'];

                foreach (array_except($data, $except) as $attribute => $value) {
                    $source->setAttribute($attribute, $value);
                }

                $source->forceSave();

                $this->logCreated();
            } catch (Throwable $e) {
                $this->logError($row, $e->getMessage());
            }
        }
    }
}
