<?php

namespace Controllers\Admin;

class Auth extends Backend {

	protected $private = false;

	protected function getLoginForm(array $values = []) {
		$form = new \Forms\Form(['method' => 'post', 'action' => '/admin/auth/attempt']);

		$form->addElement(new \Forms\Elements\Hidden('token', [
			'value' => $this->csrf->token()
		]));

		$form->addElement(new \Forms\Elements\Input('username', [
			'label' => 'Username',
			'attributes' => ['autofocus' => 'true', 'autocapitalize' => 'false', 'placeholder' => 'Username'],
		]));

		$form->addElement(new \Forms\Elements\Password('password', [
			'label' => 'Password',
			'attributes' => ['placeholder' => 'Password'],
		]));

		$form->addElement(new \Forms\Elements\Submit('submit', [
			'value' => 'Login',
			'attributes' => ['class' => 'button'],
		]));

		$form->setValues($values);

		return $form;
	}

	public function login() {
		$vars['title'] = 'Login';
		$vars['messages'] = $this->session->getFlash('messages', []);

		$values = $this->session->getFlash('input', []);
		$vars['form'] = $this->getLoginForm($values);

		return $this->renderTemplate('login', ['users/login'], $vars);
	}

	public function attempt() {
		$input = filter_input_array(INPUT_POST, [
			'token' => FILTER_SANITIZE_STRING,
			'username' => FILTER_SANITIZE_STRING,
			'password' => FILTER_UNSAFE_RAW,
		]);

		$rules = [
			'token' => ['required'],
			'username' => ['required'],
			'password' => ['required'],
		];

		// validate input
		$validator = $this->validation->create($input, $rules);

		// check token
		if($this->csrf->verify($input['token']) === false) {
			$validator->setInvalid('Invalid token');
		}

		if($validator->isValid()) {
			// check username
			$user = $this->users->fetchByUsername($input['username']);

			if(false === $user) {
				$validator->setInvalid('Invalid details');
			}
			// validate password
			elseif(false === password_verify($input['password'], $user->password)) {
				$validator->setInvalid('Invalid details');
			}
		}

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			$this->session->putFlash('input', ['username' => $input['username']]);

			return $this->redirect('/admin/auth/login');
		}

		// create session
		$this->session->put('user', $user->id);

		// redirect
		$forward = filter_input(INPUT_GET, 'forward', FILTER_SANITIZE_URL, ['options' => ['default' => '/admin/posts']]);
		return $this->redirect($forward);
	}

	public function logout() {
		$this->session->remove('user');
		$this->session->putFlash('messages', ['You are now logged out']);
		$this->redirect('/admin/auth/login');
	}

	public function start() {
		$this->redirect('/admin/auth/login');
	}

}
