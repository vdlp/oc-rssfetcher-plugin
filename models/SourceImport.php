<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Models;

use Backend\Models\ImportModel;
use Exception;

/**
 * Class SourceImport
 *
 * @package Vdlp\RssFetcher\Models
 */
class SourceImport extends ImportModel
{
    /**
     * {@inheritdoc}
     */
    public $table = 'vdlp_rssfetcher_sources';

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
    public function importData($results, $sessionKey = null)
    {
        foreach ((array) $results as $row => $data) {
            try {
                $source = Source::make();

                $except = ['id'];

                foreach (array_except($data, $except) as $attribute => $value) {
                    $source->setAttribute($attribute, $value);
                }

                $source->forceSave();

                $this->logCreated();
            } catch (Exception $e) {
                $this->logError($row, $e->getMessage());
            }
        }
    }
}
