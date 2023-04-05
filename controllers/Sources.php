<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ImportExportController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Classes\NavigationManager;
use Illuminate\Contracts\Translation\Translator;
use October\Rain\Exception\ApplicationException;
use October\Rain\Flash\FlashBag;
use Throwable;
use Vdlp\RssFetcher\Classes\RssFetcher;
use Vdlp\RssFetcher\Exceptions\SourceNotEnabledException;
use Vdlp\RssFetcher\Models\Source;

/**
 * @mixin FormController
 * @mixin ListController
 * @mixin ImportExportController
 */
final class Sources extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
        ImportExportController::class,
    ];

    public string $formConfig = 'config_form.yaml';
    public string $listConfig = 'config_list.yaml';
    public string $importExportConfig = 'config_import_export.yaml';
    protected $requiredPermissions = ['vdlp.rssfetcher.access_sources'];

    public function __construct(
        private FlashBag $flashBag,
        private Translator $translator,
    ) {
        parent::__construct();

        NavigationManager::instance()->setContext('Vdlp.RssFetcher', 'rssfetcher', 'sources');
    }

    public function onFetch(): array
    {
        try {
            $source = Source::query()->findOrFail($this->params[0]);

            if ($source instanceof Source && $source->getAttribute('is_enabled') === false) {
                throw new SourceNotEnabledException(
                    $this->translator->trans('vdlp.rssfetcher::lang.source.source_not_enabled')
                );
            }

            RssFetcher::instance()->fetch((int) $this->params[0]);

            $this->flashBag->success($this->translator->trans('vdlp.rssfetcher::lang.source.items_fetch_success'));
        } catch (SourceNotEnabledException $exception) {
            $this->flashBag->warning($exception->getMessage());
        } catch (Throwable $throwable) {
            throw new ApplicationException(
                $this->translator->trans('vdlp.rssfetcher::lang.source.items_fetch_fail', [
                    'error' => $throwable->getMessage(),
                ])
            );
        }

        return $this->listRefresh();
    }

    public function onBulkFetch(): array
    {
        foreach ($this->getCheckedIds() as $sourceId) {
            /** @var ?Source $source */
            $source = Source::query()->find($sourceId);

            if ($source === null) {
                continue;
            }

            if ($source->getAttribute('is_enabled') === false) {
                continue;
            }

            try {
                RssFetcher::instance()->fetch((int) $source->getKey());
            } catch (Throwable $e) {
                $this->flashBag->error($e->getMessage());
            }
        }

        return $this->listRefresh();
    }

    public function onDelete(): array
    {
        foreach ($this->getCheckedIds() as $sourceId) {
            /** @var ?Source $source */
            $source = Source::query()->find($sourceId);

            if ($source === null) {
                continue;
            }

            $source->delete();
        }

        return $this->listRefresh();
    }

    private function getCheckedIds(): array
    {
        $checkedIds = post('checked');

        if (is_array($checkedIds) && count($checkedIds) > 0) {
            return $checkedIds;
        }

        return [];
    }
}
