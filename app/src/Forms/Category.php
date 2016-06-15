<?php

namespace Anchorcms\Forms;

class Category extends \Forms\Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('_token'));

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
			'value' => 'Save changes',
			'attributes' => ['class' => 'button'],
		]));
	}

	public function getFilters() {
		return [
			'_token' => FILTER_SANITIZE_STRING,
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
