<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Controllers;

use Backend\Classes\NavigationManager;
use Vdlp\RssFetcher\Models\Item;
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Exception;

/**
 * Class Items
 *
 * @package Vdlp\RssFetcher\Controllers
 * @mixin FormController
 * @mixin ListController
 */
class Items extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    /**
     * @var string
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string
     */
    public $listConfig = 'config_list.yaml';

    /**
     * {@inheritdoc}
     */
    protected $requiredPermissions = ['vdlp.rssfetcher.access_items'];

    /**
     * {@inheritdoc}
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
