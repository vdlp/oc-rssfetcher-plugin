<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Classes\NavigationManager;
use Vdlp\RssFetcher\Models\Item;

/**
 * @mixin FormController
 * @mixin ListController
 */
class Items extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    protected $requiredPermissions = ['vdlp.rssfetcher.access_items'];

    public function __construct()
    {
        parent::__construct();

        NavigationManager::instance()->setContext('Vdlp.RssFetcher', 'rssfetcher', 'items');
    }

    // @codingStandardsIgnoreStart

    public function index_onDelete(): array
    {
        foreach ($this->getCheckedIds() as $sourceId) {
            if (!$source = Item::query()->find($sourceId)) {
                continue;
            }

            $source->delete();
        }

        return $this->listRefresh();
    }

    public function index_onPublish(): array
    {
        return $this->publishItem(true);
    }

    public function index_onUnpublish(): array
    {
        return $this->publishItem(false);
    }

    // @codingStandardsIgnoreEnd

    private function publishItem($publish): array
    {
        foreach ($this->getCheckedIds() as $sourceId) {
            if (!$source = Item::query()->find($sourceId)) {
                continue;
            }

            $source->update(['is_published' => $publish]);
        }

        return $this->listRefresh();
    }

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
