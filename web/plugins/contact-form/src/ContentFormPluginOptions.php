<?php
namespace RockyRoad;

use Anchorcms\Plugins\PluginOptionsInterface;
use Anchorcms\Forms\ValidatableInterface;
use Forms\Elements\Checkbox;
use Forms\Elements\Hidden;
use Forms\Elements\Submit;
use Forms\Form;

class ContentFormPluginOptions extends Form implements ValidatableInterface, PluginOptionsInterface
{
    public function init()
    {
        $this->addElement(new Hidden('_token'));
        $this->addElement(new Checkbox('backend-only', [
            'label' => 'Admin only',
            'value' => 0
        ]));
        $this->addElement(new Submit('submit', [
            'value' => 'Save changes',
            'attributes' => ['class' => 'button'],
        ]));
    }
    public function populate()
    {
        $this->getElement('backend-only')->setValue(1);
    }
    public function getFilters(): array
    {
        return [
            '_token' => FILTER_SANITIZE_STRING,
            'backend-only' => FILTER_SANITIZE_STRING
        ];
    }
    public function getRules(): array
    {
        return [
            '_token' => ['required']
        ];
    }
}
