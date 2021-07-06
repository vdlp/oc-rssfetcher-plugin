<?php

declare(strict_types=1);

namespace Vdlp\RssFetcher\FormWidgets;

use Backend\Classes\FormWidgetBase;
use SystemException;

class TextWithPrefix extends FormWidgetBase
{
    protected $defaultAlias = 'vdlp_rssfetcher_text_with_prefix';

    public function init(): void
    {
        $this->formField->config['prefix'] = '/feeds/';
    }

    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('textwithprefix');
    }

    public function prepareVars(): void
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }
}
