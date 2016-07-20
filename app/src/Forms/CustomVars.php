<?php

namespace Anchorcms\Forms;

use Forms\Form;

class CustomVars extends Form
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Input('key', [
            'label' => 'Key',
        ]));

        $this->addElement(new \Forms\Elements\Textarea('value', [
            'label' => 'Value',
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Save changes',
            'attributes' => ['class' => 'button'],
        ]));
    }

    public function getFilters(): array
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'key' => FILTER_SANITIZE_STRING,
            'value' => FILTER_UNSAFE_RAW,
        ];
    }

    public function getRules(): array
    {
        return [
            'key' => ['label' => 'Key', 'rules' => ['required']],
        ];
    }
}
