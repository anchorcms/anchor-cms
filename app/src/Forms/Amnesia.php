<?php

namespace Forms;

class Amnesia extends Form implements ValidatableInterface {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('email', [
			'label' => 'Email address',
			'attributes' => [
				'autofocus' => 'true',
				'placeholder' => 'Email address',
			],
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Reset password',
			'attributes' => [
				'class' => 'button',
			],
		]));
	}

	public function getFilters() {
		return filter_input_array(INPUT_POST, [
			'token' => FILTER_SANITIZE_STRING,
			'email' => FILTER_SANITIZE_STRING,
		]);
	}

	public function getRules() {
		return [
			'token' => ['required'],
			'email' => ['required', 'email'],
		];
	}

}
