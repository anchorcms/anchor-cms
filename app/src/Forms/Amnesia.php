<?php

namespace Anchorcms\Forms;

use Forms\Form;

class Amnesia extends Form implements ValidatableInterface
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Input('email', [
            'label' => 'Email address',
            'attributes' => [
                'autofocus' => 'true',
                'placeholder' => 'Email address',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Reset password',
            'attributes' => [
                'class' => 'button',
            ],
        ]));
    }

    public function getFilters(): array
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'email' => FILTER_SANITIZE_STRING,
        ];
    }

    public function getRules(): array
    {
        return [
            '_token' => ['required'],
            'email' => [
                'label' => 'Email', 'rules' => ['email'],
            ],
        ];
    }
}
