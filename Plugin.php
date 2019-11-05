<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace Vdlp\RssFetcher;

use Backend\Helpers\Backend as BackendHelper;
use System\Classes\PluginBase;

/**
 * Class Plugin
 *
 * @package Vdlp\RssFetcher
 */
class Plugin extends PluginBase
{
    /**
     * {@inheritDoc}
     */
    public function pluginDetails(): array
    {
        return [
            'name' => 'vdlp.rssfetcher::lang.plugin.name',
            'description' => 'vdlp.rssfetcher::lang.plugin.name',
            'author' => 'Van der Let & Partners <octobercms@vdlp.nl>',
            'icon' => 'icon-rss',
            'homepage' => 'http://github.com/vdlp/rssfetcher',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function register(): void
    {
        $this->registerConsoleCommand('Vdlp.RssFetcher', Commands\FetchRssCommand::class);
    }

    /**
     * {@inheritDoc}
     */
    public function registerComponents(): array
    {
        return [
            Components\Items::class => 'rssItems',
            Components\PaginatableItems::class => 'rssPaginatableItems',
            Components\Sources::class => 'rssSources',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function registerReportWidgets(): array
    {
        return [
            ReportWidgets\Headlines::class => [
                'label' => 'RSS Headlines',
                'code' => 'headlines',
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function registerPermissions(): array
    {
        return [
            'vdlp.rssfetcher.access_sources' => [
                'tab' => 'vdlp.rssfetcher::lang.plugin.name',
                'label' => 'vdlp.rssfetcher::lang.permissions.access_sources',
            ],
            'vdlp.rssfetcher.access_items' => [
                'tab' => 'vdlp.rssfetcher::lang.plugin.name',
                'label' => 'vdlp.rssfetcher::lang.permissions.access_items',
            ],
            'vdlp.rssfetcher.access_import_export' => [
                'tab' => 'vdlp.rssfetcher::lang.plugin.name',
                'label' => 'vdlp.rssfetcher::lang.permissions.access_import_export',
            ],
            'vdlp.rssfetcher.access_feeds' => [
                'tab' => 'vdlp.rssfetcher::lang.plugin.name',
                'label' => 'vdlp.rssfetcher::lang.permissions.access_feeds',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function registerNavigation(): array
    {
        /** @var BackendHelper $backendHelper */
        $backendHelper = resolve(BackendHelper::class);

        return [
            'rssfetcher' => [
                'label' => 'vdlp.rssfetcher::lang.navigation.menu_label',
                'url' => $backendHelper->url('vdlp/rssfetcher/sources'),
                'iconSvg' => '/plugins/vdlp/rssfetcher/assets/images/icon.svg',
                'permissions' => ['vdlp.rssfetcher.*'],
                'order' => 500,
                'sideMenu' => [
                    'sources' => [
                        'label' => 'vdlp.rssfetcher::lang.navigation.side_menu_label_sources',
                        'icon' => 'icon-globe',
                        'url' => $backendHelper->url('vdlp/rssfetcher/sources'),
                        'permissions' => ['vdlp.rssfetcher.access_sources'],
                    ],
                    'items' => [
                        'label' => 'vdlp.rssfetcher::lang.navigation.side_menu_label_items',
                        'icon' => 'icon-files-o',
                        'url' => $backendHelper->url('vdlp/rssfetcher/items'),
                        'permissions' => ['vdlp.rssfetcher.access_items'],
                    ],
                    'feeds' => [
                        'label' => 'vdlp.rssfetcher::lang.navigation.side_menu_label_feeds',
                        'icon' => 'icon-rss',
                        'url' => $backendHelper->url('vdlp/rssfetcher/feeds'),
                        'permissions' => ['vdlp.rssfetcher.access_feeds'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function registerFormWidgets(): array
    {
        return [
            FormWidgets\TextWithPrefix::class => 'textWithPrefix',
        ];
    }
}
