<?php

namespace Forms;

class Post extends Form {

	protected $filters;

	public function init() {
		$this->filters = [
			'title' => FILTER_SANITIZE_STRING,
			'content' => FILTER_UNSAFE_RAW,
			'slug' => FILTER_SANITIZE_STRING,
			'category' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_STRING,
		];

		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('title', [
			'label' => 'Title',
			'attributes' => ['placeholder' => 'Your title goes here...']
		]));

		$this->addElement(new \Forms\Elements\Input('slug', [
			'label' => 'Slug',
		]));

		$this->addElement(new \Forms\Elements\Textarea('content', [
			'label' => 'Content',
			'attributes' => ['class' => 'editor', 'placeholder' => 'Just write.']
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

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save Changes',
			'attributes' => ['class' => 'button'],
		]));
	}

	public function pushFilter($key, $value) {
		$this->filters[$key] = $value;
	}

	public function getFilters() {
		return $this->filters;
	}

	public function getRules() {
		return [
			'title' => ['label' => 'Title', 'rules' => ['required']],
		];
	}

}
