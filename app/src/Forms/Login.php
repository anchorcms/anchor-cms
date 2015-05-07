<?php

namespace Forms;

class Login extends Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('username', [
			'label' => 'Username',
			'attributes' => ['autofocus' => 'true', 'autocapitalize' => 'false', 'placeholder' => 'Username'],
		]));

		$this->addElement(new \Forms\Elements\Password('password', [
			'label' => 'Password',
			'attributes' => ['placeholder' => 'Password'],
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Login',
			'attributes' => ['class' => 'button'],
		]));
	}

	public function filter() {
		return filter_input_array(INPUT_POST, [
			'token' => FILTER_SANITIZE_STRING,
			'username' => FILTER_SANITIZE_STRING,
			'password' => FILTER_UNSAFE_RAW,
		]);
	}

	public function rules() {
		return [
			'token' => ['required'],
			'username' => ['required'],
			'password' => ['required'],
		];
	}

}
