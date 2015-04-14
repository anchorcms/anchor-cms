<?php

namespace Forms\Installer;

class Account extends \Forms\Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Input('username', [
			'label' => 'Username',
			'value' => 'admin',
		]));

		$this->addElement(new \Forms\Elements\Input('email', [
			'label' => 'Email Address',
		]));

		$this->addElement(new \Forms\Elements\Password('password', [
			'label' => 'Password',
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Complete',
			'attributes' => ['class' => 'button primary'],
		]));
	}

}
