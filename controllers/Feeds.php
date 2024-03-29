<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Classes\NavigationManager;

/**
 * @mixin FormController
 * @mixin ListController
 */
final class Feeds extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    public string $formConfig = 'config_form.yaml';
    public string $listConfig = 'config_list.yaml';
    protected $requiredPermissions = ['vdlp.rssfetcher.access_feeds'];

    public function __construct()
    {
        parent::__construct();

        NavigationManager::instance()->setContext('Vdlp.RssFetcher', 'rssfetcher', 'feeds');
    }
}
