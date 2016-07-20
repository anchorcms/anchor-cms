<?php

namespace Anchorcms\Forms;

use Forms\Form;

class Reset extends Form implements ValidatableInterface
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Password('password', [
            'label' => 'Reset Password',
            'attributes' => [
                'autofocus' => 'true',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Update password',
            'attributes' => [
                'class' => 'button',
            ],
        ]));
    }

    public function getFilters(): array
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'password' => FILTER_UNSAFE_RAW,
        ];
    }

    public function getRules(): array
    {
        return [
            '_token' => ['required'],
            'password' => [
                'label' => 'Password', 'rules' => ['required'],
            ],
        ];
    }
}
