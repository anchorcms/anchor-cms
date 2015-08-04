<?php

namespace Forms;

class Category extends Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('title', [
			'label' => 'Title',
			'attributes' => [
        		'autofocus' => true,
        		'placeholder' => 'What’s your category called?',
        		'class' => 'title'
        	]
		]));

		$this->addElement(new \Forms\Elements\Input('slug', [
			'label' => 'Slug',
		]));

		$this->addElement(new \Forms\Elements\Input('description', [
			'label' => 'Description',
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save Changes',
			'attributes' => ['class' => 'button'],
		]));
	}

	public function getFilters() {
		return [
			'token' => FILTER_SANITIZE_STRING,
			'title' => FILTER_SANITIZE_STRING,
			'slug' => FILTER_SANITIZE_STRING,
			'description' => FILTER_SANITIZE_STRING,
		];
	}

	public function getRules() {
		return [
			'title' => ['label' => 'Title', 'rules' => ['required']],
		];
	}

}
