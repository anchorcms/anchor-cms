<?php

namespace Anchorcms\Forms;

class CustomField extends \Forms\Form
{

    public function init()
    {
        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Select('type', [
            'label' => 'Content Type',
            'options' => [
                'post' => 'Post',
                'page' => 'Page',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Select('field', [
            'label' => 'Input Type',
            'options' => [
                'text' => 'Text',
                'html' => 'Html',
                'image' => 'Image',
                'file' => 'File',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Input('key', [
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

    public function getFilters()
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'type' => FILTER_SANITIZE_STRING,
            'field' => FILTER_SANITIZE_STRING,
            'key' => FILTER_SANITIZE_STRING,
            'label' => FILTER_SANITIZE_STRING,
        ];
    }

    public function getRules()
    {
        return [
            'type' => ['label' => 'Content Type', 'rules' => ['required']],
            'field' => ['label' => 'Input Type', 'rules' => ['required']],
            'key' => ['label' => 'Key', 'rules' => ['required']],
            'label' => ['label' => 'Label', 'rules' => ['required']],
        ];
    }
}
