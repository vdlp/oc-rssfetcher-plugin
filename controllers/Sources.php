<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ImportExportController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Classes\NavigationManager;
use Exception;
use October\Rain\Exception\ApplicationException;
use October\Rain\Flash\FlashBag;
use October\Rain\Translation\Translator;
use Vdlp\RssFetcher\Classes\RssFetcher;
use Vdlp\RssFetcher\Exceptions\SourceNotEnabledException;
use Vdlp\RssFetcher\Models\Source;

/**
 * Sources Back-end Controller
 *
 * @package Vdlp\RssFetcher\Controllers
 * @mixin FormController
 * @mixin ListController
 * @mixin ImportExportController
 */
class Sources extends Controller
{
    /**
     * {@inheritDoc}
     */
    public $implement = [
        FormController::class,
        ListController::class,
        ImportExportController::class,
    ];

    /**
     * {@inheritDoc}
     */
    public $formConfig = 'config_form.yaml';

    /**
     * {@inheritDoc}
     */
    public $listConfig = 'config_list.yaml';

    /**
     * {@inheritDoc}
     */
    public $importExportConfig = 'config_import_export.yaml';

    /**
     * {@inheritDoc}
     */
    protected $requiredPermissions = ['vdlp.rssfetcher.access_sources'];

    /**
     * @var FlashBag
     */
    private $flashBag;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->flashBag = resolve('flash');
        $this->translator = resolve('translator');

        NavigationManager::instance()->setContext('Vdlp.RssFetcher', 'rssfetcher', 'sources');
    }

    /**
     * Fetches RSS items from source
     *
     * @throws ApplicationException
     * @return array
     */
    public function onFetch(): array
    {
        try {
            $source = Source::query()->findOrFail($this->params[0]);

            if ($source instanceof Source && !$source->getAttribute('is_enabled')) {
                throw new SourceNotEnabledException(
                    $this->translator->trans('vdlp.rssfetcher::lang.source.source_not_enabled')
                );
            }

            RssFetcher::instance()->fetch((int) $this->params[0]);

            $this->flashBag->success($this->translator->trans('vdlp.rssfetcher::lang.source.items_fetch_success'));
        } catch (SourceNotEnabledException $e) {
            $this->flashBag->warning($e->getMessage());
        } catch (Exception $e) {
            throw new ApplicationException(
                $this->translator->trans('vdlp.rssfetcher::lang.source.items_fetch_fail', [
                    'error' => $e->getMessage()
                ])
            );
        }

        return $this->listRefresh();
    }

    // @codingStandardsIgnoreStart

    /**
     * @return array
     */
    public function index_onBulkFetch(): array
    {
        foreach ($this->getCheckedIds() as $sourceId) {
            if (!$source = Source::query()->find($sourceId)) {
                continue;
            }

            if (!$source->getAttribute('is_enabled')) {
                continue;
            }

            try {
                RssFetcher::instance()->fetch((int) $source->getKey());
            } catch (Exception $e) {
                $this->flashBag->error($e->getMessage());
            }
        }

        return $this->listRefresh();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function index_onDelete(): array
    {
        foreach ($this->getCheckedIds() as $sourceId) {
            if (!$source = Source::query()->find($sourceId)) {
                continue;
            }

            $source->delete();
        }

        return $this->listRefresh();
    }

    // @codingStandardsIgnoreEnd

    /**
     * Check checked ID's from POST request.
     *
     * @return array
     */
    private function getCheckedIds(): array
    {
        if (($checkedIds = post('checked'))
            && is_array($checkedIds)
            && count($checkedIds)
        ) {
            return $checkedIds;
        }

        return [];
    }
}
