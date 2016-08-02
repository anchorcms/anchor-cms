<?php

namespace Anchorcms\Forms\Installer;

use Forms\Form;

class L10n extends Form
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Select('app_lang', [
            'label' => 'Language',
            'options' => ['en_GB' => 'English'],
        ]));

        $list = \DateTimeZone::listIdentifiers();

        $this->addElement(new \Forms\Elements\Select('app_timezone', [
            'label' => 'Timezone',
            'options' => array_combine($list, $list),
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Next',
            'attributes' => ['class' => 'button button--primary'],
        ]));
    }

    public function getFilters(): array
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'app_lang' => FILTER_SANITIZE_STRING,
            'app_timezone' => FILTER_SANITIZE_STRING,
        ];
    }

    public function getRules(): array
    {
        return [
            '_token' => ['label' => 'Token', 'rules' => ['required']],
            'app_lang' => ['label' => 'Language', 'rules' => ['required']],
            'app_timezone' => ['label' => 'Time Zone', 'rules' => ['required']],
        ];
    }
}
