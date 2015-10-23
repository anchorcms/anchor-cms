<?php

namespace Controllers\Admin;

class Auth extends Backend {

	protected $private = false;

	public function getLogin() {
		$vars['title'] = 'Login';
		$vars['messages'] = $this->messages->render($this->getViewPath().'/messages.phtml');

		$form = new \Forms\Login(['method' => 'post', 'action' => '/admin/auth/attempt']);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());

		$values = $this->session->getFlash('input', []);
		$form->setValues($values);

		$vars['form'] = $form;

		return $this->renderTemplate('login', ['users/login'], $vars);
	}

	public function postAttempt() {
		$form = new \Forms\Login;
		$input = $form->filter();

		// validate input
		$validator = $this->validation->create($input, $form->rules());

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
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', ['username' => $input['username']]);

			return $this->redirect('/admin/auth/login');
		}

		// create session
		$this->session->put('user', $user->id);

		// redirect
		$forward = filter_input(INPUT_GET, 'forward', FILTER_SANITIZE_URL, ['options' => ['default' => '/admin/posts']]);
		return $this->redirect($forward);
	}

	public function getLogout() {
		$this->session->regenerate(true);
		$this->messages->success('You are now logged out');
		return $this->redirect('/admin/auth/login');
	}

	public function getStart() {
		return $this->redirect('/admin/auth/login');
	}

}
