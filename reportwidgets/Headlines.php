<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\ReportWidgets;

use Vdlp\RssFetcher\Components\Items;
use Backend\Classes\ReportWidgetBase;
use Lang;

/**
 * Class Headlines
 *
 * @package Vdlp\RssFetcher\ReportWidgets
 */
class Headlines extends ReportWidgetBase
{
    /**
     * @return array
     */
    public function widgetDetails(): array
    {
        return [
            'name' => 'vdlp.rssfetcher::lang.report_widget.headlines.name',
            'description' => 'vdlp.rssfetcher::lang.report_widget.headlines.name',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function defineProperties(): array
    {
        return [
            'title' => [
                'title' => 'vdlp.rssfetcher::lang.report_widget.headlines.title_title',
                'default' => Lang::get('vdlp.rssfetcher::lang.report_widget.headlines.title_default'),
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'vdlp.rssfetcher::lang.report_widget.headlines.title_required',
            ],
            'maxItems' => [
                'title' => 'vdlp.rssfetcher::lang.report_widget.headlines.max_items_title',
                'default' => '5',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
            ],
            'dateFormat' => [
                'title' => 'vdlp.rssfetcher::lang.report_widget.headlines.date_format_title',
                'description' => 'vdlp.rssfetcher::lang.report_widget.headlines.date_format_description',
                'default' => 'Y-m-d H:i',
                'type' => 'string',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->vars['title'] = $this->property('title');
        $this->vars['items'] = Items::loadItems((int) $this->property('maxItems', 10));
        $this->vars['dateFormat'] = $this->property('dateFormat');

        return $this->makePartial('widget');
    }
}
