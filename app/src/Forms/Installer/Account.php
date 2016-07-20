<?php

namespace Anchorcms\Forms\Installer;

use Forms\Form;

class Account extends Form
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Input('account_username', [
            'label' => 'Username',
            'value' => 'admin',
        ]));

        $this->addElement(new \Forms\Elements\Input('account_email', [
            'label' => 'Email Address',
        ]));

        $this->addElement(new \Forms\Elements\Password('account_password', [
            'label' => 'Password',
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Complete',
            'attributes' => ['class' => 'button button--primary float--right'],
        ]));
    }

    public function getFilters(): array
    {
        return [
            'account_username' => FILTER_SANITIZE_STRING,
            'account_email' => FILTER_SANITIZE_STRING,
            'account_password' => FILTER_UNSAFE_RAW,
        ];
    }

    public function getRules(): array
    {
        return [
            'account_username' => ['label' => 'Username', 'rules' => ['length:3,']],
            'account_email' => ['label' => 'Email Address', 'rules' => ['email']],
            'account_password' => ['label' => 'Password', 'rules' => ['length:8,']],
        ];
    }
}
