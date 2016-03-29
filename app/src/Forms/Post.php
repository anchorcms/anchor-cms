<?php

namespace Forms;

use Forms\Traits\Filters;
use Forms\Traits\FilterRules;

class Post extends Form {

	use Filters, FilterRules;

	public function init() {
		$this->setFilters([
			'_token' => FILTER_SANITIZE_STRING,
			'title' => FILTER_SANITIZE_STRING,
			'content' => FILTER_UNSAFE_RAW,
			'slug' => FILTER_SANITIZE_STRING,
			'category' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_STRING,
			'published' => FILTER_SANITIZE_STRING,
		]);

		$this->setRules([
			'title' => ['label' => 'Title', 'rules' => ['required']],
		]);

		$this->addElement(new \Forms\Elements\Hidden('_token'));

		$this->addElement(new \Forms\Elements\Input('title', [
			'label' => 'Title',
			'attributes' => [
				'autofocus' => true,
				'placeholder' => 'What’s your post called?',
				'class' => 'title'
			]
		]));

		$this->addElement(new \Forms\Elements\Input('slug', [
			'label' => 'Slug',
		]));

		$this->addElement(new \Forms\Elements\Textarea('content', [
			'label' => 'Content',
			'attributes' => ['class' => 'markdown-editor', 'placeholder' => 'Just write.']
		]));

		$this->addElement(new \Forms\Elements\Select('category', [
			'label' => 'Category',
		]));

		$this->addElement(new \Forms\Elements\Select('status', [
			'label' => 'Status',
			'options' => [
				'draft' => 'Draft',
				'published' => 'Published',
				'archived' => 'Archived',
			],
		]));

		$this->addElement(new \Forms\Elements\Input('published', [
			'label' => 'Published Date',
			'value' => '0000-00-00 00:00:00',
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save changes',
			'attributes' => ['class' => 'button'],
		]));
	}

}
