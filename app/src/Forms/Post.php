<?php

namespace Anchorcms\Forms;

use Forms\Form;
use Anchorcms\Forms\Traits\FormFilters;
use Anchorcms\Forms\Traits\FormRules;

class Post extends Form
{
    use FormFilters, FormRules;

    public function init()
    {
        $this->setFilters([
            '_token' => [
                'filter' => FILTER_SANITIZE_STRING,
                'options' => [
                    'default' => '',
                ],
            ],
            'title' => [
                'filter' => FILTER_SANITIZE_STRING,
                'options' => [
                    'default' => '',
                ],
            ],
            'content' => [
                'filter' => FILTER_UNSAFE_RAW,
                'options' => [
                    'default' => '',
                ],
            ],
            'slug' => [
                'filter' => FILTER_SANITIZE_STRING,
                'options' => [
                    'default' => '',
                ],
            ],
            'category' => [
                'filter' => FILTER_SANITIZE_NUMBER_INT,
                'options' => [
                    'default' => 0,
                ],
            ],
            'status' => [
                'filter' => FILTER_SANITIZE_STRING,
                'options' => [
                    'default' => '',
                ],
            ],
            'published' => [
                'filter' => FILTER_SANITIZE_STRING,
                'options' => [
                    'default' => '',
                ],
            ],
        ]);

        $this->setRules([
            'title' => ['label' => 'Title', 'rules' => ['required']],
        ]);

        $this->append(new \Forms\Elements\Hidden('_token'));

        $this->append(new \Forms\Elements\Input('title', [
            'label' => 'Title',
            'attributes' => [
                'autofocus' => true,
                'placeholder' => 'What’s your post called?',
                'class' => 'title',
            ],
        ]));

        $this->append(new \Forms\Elements\Input('slug', [
            'label' => 'Slug',
        ]));

        $this->append(new \Forms\Elements\Textarea('content', [
            'label' => 'Content',
            'attributes' => ['class' => 'markdown-editor', 'placeholder' => 'Just write.'],
        ]));

        $this->append(new \Forms\Elements\Select('category', [
            'label' => 'Category',
        ]));

        $this->append(new \Forms\Elements\Select('status', [
            'label' => 'Status',
            'options' => [
                'draft' => 'Draft',
                'published' => 'Published',
                'archived' => 'Archived',
            ],
        ]));

        $this->append(new \Forms\Elements\Input('published', [
            'label' => 'Published Date',
            'value' => date('Y-m-d H:i:s'),
        ]));

        $this->append(new \Forms\Elements\Submit('submit', [
            'value' => 'Save Changes',
            'attributes' => [
                'class' => 'button button--primary',
            ],
        ]));
    }
}
