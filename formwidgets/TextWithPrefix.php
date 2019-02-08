<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\FormWidgets;

use Backend\Classes\FormWidgetBase;
use SystemException;

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
    public function init(): void
    {
        $this->formField->config['prefix'] = '/feeds/';
    }

    /**
     * {@inheritdoc}
     * @throws SystemException
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('textwithprefix');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars(): void
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }
}
