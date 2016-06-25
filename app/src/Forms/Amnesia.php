<?php

namespace Anchorcms\Forms;

class Amnesia extends \Forms\Form implements ValidatableInterface
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

    public function getFilters()
    {
        return filter_input_array(INPUT_POST, [
            '_token' => FILTER_SANITIZE_STRING,
            'email' => FILTER_SANITIZE_STRING,
        ]);
    }

    public function getRules()
    {
        return [
            '_token' => ['required'],
            'email' => [
                'label' => 'Email', 'rules' => ['email'],
            ],
        ];
    }
}
