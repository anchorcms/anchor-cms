<?php

namespace Anchorcms\Forms;

use Forms\Traits\Filters;
use Forms\Traits\FilterRules;

class Page extends \Forms\Form
{
    use Filters, FilterRules;

    public function init()
    {
        $this->setFilters([
            '_token' => FILTER_SANITIZE_STRING,
            'parent' => FILTER_SANITIZE_NUMBER_INT,
            'slug' => FILTER_SANITIZE_STRING,
            'name' => FILTER_SANITIZE_STRING,
            'title' => FILTER_SANITIZE_STRING,
            'content' => FILTER_UNSAFE_RAW,
            'status' => FILTER_SANITIZE_STRING,
            'redirect' => FILTER_SANITIZE_STRING,
            'show_in_menu' => FILTER_SANITIZE_NUMBER_INT,
            'menu_order' => FILTER_SANITIZE_NUMBER_INT,
        ]);

        $this->setRules([
            'title' => ['label' => 'Title', 'rules' => ['required']],
        ]);

        $this->addElement(new \Forms\Elements\Hidden('_token'));

        $this->addElement(new \Forms\Elements\Select('parent', [
            'label' => 'Parent',
        ]));

        $this->addElement(new \Forms\Elements\Input('slug', [
            'label' => 'Slug',
        ]));

        $this->addElement(new \Forms\Elements\Input('name', [
            'label' => 'Menu Name',
        ]));

        $this->addElement(new \Forms\Elements\Input('title', [
            'label' => 'Title',
            'attributes' => [
                'autofocus' => true,
                'placeholder' => 'What’s your page called?',
                'class' => 'title',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Textarea('content', [
            'label' => 'Content',
            'attributes' => ['class' => 'markdown-editor', 'placeholder' => 'Just write.'],
        ]));

        $this->addElement(new \Forms\Elements\Select('status', [
            'label' => 'Status',
            'options' => [
                'draft' => 'Draft',
                'published' => 'Published',
                'archived' => 'Archived',
            ],
        ]));

        $this->addElement(new \Forms\Elements\Input('redirect', [
            'label' => 'Redirect',
        ]));

        $this->addElement(new \Forms\Elements\Checkbox('show_in_menu', [
            'label' => 'Show in Menu',
            'value' => 1,
        ]));

        $this->addElement(new \Forms\Elements\Input('menu_order', [
            'label' => 'Menu Order',
            'value' => 0,
        ]));

        $this->addElement(new \Forms\Elements\Submit('submit', [
            'value' => 'Save changes',
            'attributes' => ['class' => 'button'],
        ]));
    }
}
