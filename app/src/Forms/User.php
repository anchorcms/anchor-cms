<?php

namespace Forms;

class User extends Form {

	public function init() {
		$this->addElement(new \Forms\Elements\Hidden('_token'));

		$this->addElement(new \Forms\Elements\Input('name', [
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
				'editor' => 'Editor',
				'author' => 'Author',
				'subscriber' => 'Subscriber',
			],
		]));

		$this->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Save changes',
			'attributes' => ['class' => 'button'],
		]));
	}

	public function getFilters() {
		return [
			'_token' => FILTER_SANITIZE_STRING,
			'username' => FILTER_SANITIZE_STRING,
			'password' => FILTER_UNSAFE_RAW,
			'email' => FILTER_SANITIZE_STRING,
			'name' => FILTER_SANITIZE_STRING,
			'bio' => FILTER_SANITIZE_STRING,
			'status' => FILTER_SANITIZE_STRING,
			'role' => FILTER_SANITIZE_STRING,
		];
	}

	public function getRules() {
		return [
			'username' => ['label' => 'Username', 'rules' => ['length:4,200']],
			'password' => ['label' => 'Password', 'rules' => ['length:8,200']],
			'email' => ['label' => 'Email Address', 'rules' => ['email']],
			'name' => ['label' => 'Name', 'rules' => ['required']],
			'status' => ['label' => 'Status', 'rules' => ['required']],
			'role' => ['label' => 'Role', 'rules' => ['required']],
		];
	}

}
