<?php

namespace Forms;

class User extends Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('token'));

		$this->addElement(new \Forms\Elements\Input('real_name', [
			'label' => 'Name',
		]));

		$this->addElement(new \Forms\Elements\Input('username', [
			'label' => 'Username',
		]));

		$this->addElement(new \Forms\Elements\Password('password', [
			'label' => 'Password',
		]));

		$this->addElement(new \Forms\Elements\Input('email', [
			'label' => 'Email Address',
		]));

		$this->addElement(new \Forms\Elements\Textarea('bio', [
			'label' => 'Bio',
		]));

		$this->addElement(new \Forms\Elements\Select('status', [
			'label' => 'Status',
			'options' => [
				'inactive' => 'Inactive',
				'active' => 'Active',
			],
		]));

		$this->addElement(new \Forms\Elements\Select('role', [
			'label' => 'Role',
			'options' => [
				'admin' => 'Admin',
			],
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save Changes',
			'attributes' => ['class' => 'button'],
		]));
	}

	public function getFilters() {
		return [
			'token' => FILTER_SANITIZE_STRING,
			'username' => FILTER_SANITIZE_STRING,
			'password' => FILTER_UNSAFE_RAW,
			'email' => FILTER_SANITIZE_STRING,
			'real_name' => FILTER_SANITIZE_STRING,
			'bio' => FILTER_SANITIZE_STRING,
			'status' => FILTER_SANITIZE_STRING,
			'role' => FILTER_SANITIZE_STRING,
		];
	}

	public function getRules() {
		return [
			'username' => ['label' => 'Username', 'rules' => ['required', 'length:4,200']],
			'password' => ['label' => 'Password', 'rules' => ['required', 'length:8,200']],
			'email' => ['label' => 'Email Address', 'rules' => ['required', 'email']],
			'real_name' => ['label' => 'Real Name', 'rules' => ['required']],
			'status' => ['label' => 'Status', 'rules' => ['required']],
			'role' => ['label' => 'Role', 'rules' => ['required']],
		];
	}

}
