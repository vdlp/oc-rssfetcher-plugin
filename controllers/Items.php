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
final class Items extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    public string $listConfig = 'config_list.yaml';
    public string $formConfig = 'config_form.yaml';
    protected $requiredPermissions = ['vdlp.rssfetcher.access_items'];

    public function __construct()
    {
        parent::__construct();

        NavigationManager::instance()->setContext('Vdlp.RssFetcher', 'rssfetcher', 'items');
    }

    public function onDelete(): array
    {
        foreach ($this->getCheckedIds() as $itemId) {
            /** @var ?Item $item */
            $item = Item::query()->find($itemId);

            if ($item === null) {
                continue;
            }

            $item->delete();
        }

        return $this->listRefresh();
    }

    public function onPublish(): array
    {
        return $this->publishItem(true);
    }

    public function onUnpublish(): array
    {
        return $this->publishItem(false);
    }

    private function publishItem(bool $publish): array
    {
        foreach ($this->getCheckedIds() as $itemId) {
            /** @var ?Item $item */
            $item = Item::query()->find($itemId);

            if ($item === null) {
                continue;
            }

            $item->update(['is_published' => $publish]);
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
