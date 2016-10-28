<?php

namespace Anchorcms\Forms\Installer;

use Forms\Form;

class Metadata extends Form
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Input('site_name', [
            'label' => 'Site Name',
            'value' => 'My First Anchor Blog',
        ]));

        $this->addElement(new \Forms\Elements\Input('site_description', [
            'label' => 'Site Description',
            'value' => 'It’s not just any blog. It’s an Anchor blog.',
        ]));

        $this->addElement(new \Forms\Elements\Input('site_path', [
            'label' => 'Site Path',
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Next',
            'attributes' => ['class' => 'button button--primary float--right'],
        ]));
    }

    public function getFilters(): array
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'site_name' => FILTER_SANITIZE_STRING,
            'site_description' => FILTER_SANITIZE_STRING,
            'site_path' => FILTER_SANITIZE_STRING,
        ];
    }

    public function getRules(): array
    {
        return [
            '_token' => ['label' => 'Token', 'rules' => ['required']],
            'site_name' => ['label' => 'Site Name', 'rules' => ['required']],
            'site_description' => ['label' => 'Site Description', 'rules' => ['required']],
            'site_path' => ['label' => 'Site Path', 'rules' => ['required']],
        ];
    }
}
