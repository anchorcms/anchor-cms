<?php

namespace Anchorcms\Forms;

use Forms\Form;

class Login extends Form implements ValidatableInterface
{
    public function init()
    {
        $this->append(new \Forms\Elements\Hidden('_token'));

        $this->append(new \Forms\Elements\Input('username', [
            'label' => 'Username',
            'attributes' => [
                'autofocus' => 'true',
                'placeholder' => 'Username',
            ],
        ]));

        $this->append(new \Forms\Elements\Password('password', [
            'label' => 'Password',
            'attributes' => [
                'placeholder' => 'Password',
            ],
        ]));

        $this->append(new \Forms\Elements\Submit('submit', [
            'value' => 'Log In',
            'attributes' => [
                'class' => 'button button--dark button--wide',
            ],
        ]));
    }

    public function getFilters()
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'username' => FILTER_SANITIZE_STRING,
            'password' => FILTER_UNSAFE_RAW,
        ];
    }

    public function getRules()
    {
        return [
            '_token' => ['required'],
            'username' => [
                'label' => 'Username',
                'rules' => ['required'],
            ],
            'password' => [
                'label' => 'Password',
                'rules' => ['required'],
            ],
        ];
    }
}
