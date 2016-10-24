<?php

namespace Anchorcms\Forms;

use Forms\Form;

class CustomField extends Form
{
    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Select('content_type', [
            'label' => 'Content Type',
            'options' => [
                'post' => 'Post',
                'page' => 'Page',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Select('input_type', [
            'label' => 'Input Type',
            'options' => [
                'text' => 'Text',
                'html' => 'Html',
                'image' => 'Image',
                'file' => 'File',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Input('field_key', [
            'label' => 'Key',
        ]));

        $this->addElement(new \Forms\Elements\Input('label', [
            'label' => 'Label',
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
            'content_type' => FILTER_SANITIZE_STRING,
            'input_type' => FILTER_SANITIZE_STRING,
            'field_key' => FILTER_SANITIZE_STRING,
            'label' => FILTER_SANITIZE_STRING,
        ];
    }

    public function getRules(): array
    {
        return [
            'content_type' => ['label' => 'Content Type', 'rules' => ['required']],
            'input_type' => ['label' => 'Input Type', 'rules' => ['required']],
            'field_key' => ['label' => 'Key', 'rules' => ['required']],
            'label' => ['label' => 'Label', 'rules' => ['required']],
        ];
    }
}
