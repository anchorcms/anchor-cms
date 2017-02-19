<?php

namespace Anchorcms\Forms;

use Forms\Form;

class CustomVars extends Form
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Input('meta_key', [
            'label' => 'Key',
        ]));

        $this->addElement(new \Forms\Elements\Textarea('meta_value', [
            'label' => 'Value',
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value'      => 'Save changes',
            'attributes' => ['class' => 'button'],
        ]));
    }

    public function getFilters(): array
    {
        return [
            '_token'     => FILTER_SANITIZE_STRING,
            'meta_key'   => FILTER_SANITIZE_STRING,
            'meta_value' => FILTER_UNSAFE_RAW,
        ];
    }

    public function getRules(): array
    {
        return [
            'meta_key' => ['label' => 'Key', 'rules' => ['required']],
        ];
    }
}
