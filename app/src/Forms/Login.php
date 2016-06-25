<?php

namespace Anchorcms\Forms;

class Login extends \Forms\Form implements ValidatableInterface
{

    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Input('username', [
            'label' => 'Username',
            'attributes' => [
                'autofocus' => 'true',
                'placeholder' => 'Username',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Password('password', [
            'label' => 'Password',
            'attributes' => [
                'placeholder' => 'Password',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Log In',
            'attributes' => [
                'class' => 'button button--dark button--wide',
            ],
        ]));
    }

    public function getFilters()
    {
        return filter_input_array(INPUT_POST, [
            '_token' => FILTER_SANITIZE_STRING,
            'username' => FILTER_SANITIZE_STRING,
            'password' => FILTER_UNSAFE_RAW,
        ]);
    }

    public function getRules()
    {
        return [
            '_token' => ['required'],
            'username' => [
                'label' => 'Username', 'rules' => ['required'],
            ],
            'password' => [
                'label' => 'Password', 'rules' => ['required'],
            ],
        ];
    }
}
