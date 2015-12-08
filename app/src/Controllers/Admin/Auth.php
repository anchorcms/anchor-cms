<?php

namespace Controllers\Admin;

class Auth extends Backend {

	protected $private = false;

	public function getLogin() {
		$vars['title'] = 'Login';
		$vars['messages'] = $this->messages->render();

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
		$input = $form->getFilters();

		// validate input
		$validator = $this->validation->create($input, $form->getRules());

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
			elseif(false === $user->isPassword($input['password'])) {
				$validator->setInvalid('Invalid details');
			}
			// is active
			elseif(false === $user->isActive()) {
				$validator->setInvalid('Your account is inactive');
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
		if($this->session->has('user')) {
			$forward = filter_input(INPUT_GET, 'forward', FILTER_SANITIZE_URL, ['options' => ['default' => '/admin/posts']]);
			return $this->redirect($forward);
		}
		return $this->redirect('/admin/auth/login');
	}

}
