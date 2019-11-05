<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\NavigationManager;
use Backend\Classes\Controller;

/**
 * Class Feeds
 *
 * @package Vdlp\RssFetcher\Controllers
 * @mixin FormController
 * @mixin ListController
 */
class Feeds extends Controller
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
    public $formConfig = 'config_form.yaml';

    /**
     * {@inheritDoc}
     */
    public $listConfig = 'config_list.yaml';

    /**
     * {@inheritDoc}
     */
    protected $requiredPermissions = ['vdlp.rssfetcher.access_feeds'];

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();

        NavigationManager::instance()->setContext('Vdlp.RssFetcher', 'rssfetcher', 'feeds');
    }
}
