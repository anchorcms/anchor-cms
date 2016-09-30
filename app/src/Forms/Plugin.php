<?php

namespace Anchorcms\Forms;

use Forms\Form;
use Forms\Elements;

class Plugin extends Form implements ValidatableInterface
{
    public function init()
    {
        $this->append(new Elements\Hidden('_token'));

        $this->append(new Elements\File('file', [
            'label' => 'Plugin file',
            'attributes' => [
                'accept' => 'application/zip, application/octet-stream',
                'required' => ''
            ],
        ]));

        $this->append(new Elements\Submit('submit', [
            'value' => 'Upload',
            'attributes' => [
                'class' => 'button',
            ],
        ]));
    }

    public function getFilters(): array
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
        ];
    }

    public function getRules(): array
    {
        return [
            '_token' => ['required'],
        ];
    }
}
