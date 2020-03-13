<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Classes\NavigationManager;
use Exception;
use Vdlp\RssFetcher\Models\Item;

/**
 * Class Items
 * @mixin FormController
 * @mixin ListController
 */
class Items extends Controller
{
    /**
     * {@inheritDoc}
     */
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    /**
     * {@inheritDoc}
     */
    public $listConfig = 'config_list.yaml';

    /**
     * {@inheritDoc}
     */
    public $formConfig = 'config_form.yaml';

    /**
     * {@inheritDoc}
     */
    protected $requiredPermissions = ['vdlp.rssfetcher.access_items'];

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        NavigationManager::instance()->setContext('Vdlp.RssFetcher', 'rssfetcher', 'items');
    }

    // @codingStandardsIgnoreStart

    /**
     * @return array
     * @throws Exception
     */
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

    /**
     * @return array
     */
    public function index_onPublish(): array
    {
        return $this->publishItem(true);
    }

    /**
     * @return array
     */
    public function index_onUnpublish(): array
    {
        return $this->publishItem(false);
    }

    // @codingStandardsIgnoreEnd

    /**
     * @param $publish
     * @return array
     */
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
