<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * Class TextWithPrefix
 *
 * @package Vdlp\RssFetcher\FormWidgets
 */
class TextWithPrefix extends FormWidgetBase
{
    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'vdlp_rssfetcher_text_with_prefix';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->formField->config['prefix'] = '/feeds/';
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('textwithprefix');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }
}
