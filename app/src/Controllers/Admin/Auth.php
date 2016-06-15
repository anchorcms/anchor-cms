<?php

namespace Anchorcms\Controllers\Admin;

class Auth extends Backend {

	protected $private = false;

	public function getStart() {
		if($this->container['session']->has('user')) {
			$forward = filter_input(INPUT_GET, 'forward', FILTER_SANITIZE_URL, [
				'options' => [
					'default' => $this->container['url']->to('/admin/posts'),
				],
			]);

			return $this->redirect($forward);
		}
		return $this->redirect($this->container['url']->to('/admin/auth/login'));
	}

	public function getLogin() {
		$vars['title'] = 'Login';

		$form = new \Forms\Login([
			'method' => 'post',
			'action' => $this->container['url']->to('/admin/auth/attempt'),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());

		$values = $this->container['session']->getFlash('input', []);
		$form->setValues($values);

		$vars['form'] = $form;

		return $this->renderTemplate('layouts/minimal', 'users/login', $vars);
	}

	public function postAttempt() {
		$form = new \Forms\Login;
		$input = $form->getFilters();

		// validate input
		$validator = $this->container['validation']->create($input, $form->getRules());

		// check token
		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if($validator->isValid()) {
			$user = $this->container['services.auth']->login($input['username'], $input['password']);

			if(false === $user) {
				$validator->setInvalid('Sorry, we don&apos;t reconise those details');
			}
			elseif(false === $user->isActive()) {
				$validator->setInvalid('Your account have not active');
			}
		}

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', ['username' => $input['username']]);

			return $this->redirect($this->container['url']->to('/admin/auth/login'));
		}

		// check password and update it
		if($this->container['services.auth']->checkPasswordHash($user->password)) {
			$this->container['services.auth']->changePassword($user, $input['password']);
		}

		// create session
		$this->container['session']->put('user', $user->id);

		// redirect
		$forward = filter_input(INPUT_GET, 'forward', FILTER_SANITIZE_URL, [
			'options' => [
				'default' => $this->container['url']->to('/admin/posts'),
			],
		]);

		return $this->redirect($forward);
	}

	public function getLogout() {
		$this->container['session']->clear();
		$this->container['session']->regenerate(true);

		$this->container['messages']->success('You are now logged out');
		return $this->redirect($this->container['url']->to('/admin/auth/login'));
	}

	public function getAmnesia() {
		$vars['title'] = 'Forgotten Password';

		$form = new \Forms\Amnesia([
			'method' => 'post',
			'action' => $this->container['url']->to('/admin/auth/amnesia'),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());

		$values = $this->container['session']->getFlash('input', []);
		$form->setValues($values);

		$vars['form'] = $form;

		return $this->renderTemplate('layouts/minimal', 'users/amnesia', $vars);
	}

	public function postAmnesia($request) {
		$form = new \Forms\Amnesia;
		$input = $form->getFilters();

		// validate input
		$validator = $this->container['validation']->create($input, $form->getRules());

		// check token
		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if($validator->isValid()) {
			// check username
			$user = $this->container['mappers.users']->fetchByEmail($input['email']);

			if(false === $user) {
				$validator->setInvalid('Sorry, we don&apos;t reconise those details');
			}
			// is active
			elseif(false === $user->isActive()) {
				$validator->setInvalid('Your account have not active');
			}
		}

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', ['email' => $input['email']]);

			return $this->redirect($this->container['url']->to('/admin/auth/amnesia'));
		}

		$to = [$user->email => $user->name];
		$from = $this->container['config']->get('mail.from');
		$subject = sprintf('[%s] Password Reset', $this->container['mappers.meta']->key('sitename'));

		$token = $this->container['services.auth']->resetToken($user);

		$link = $this->container['url']->to('/admin/auth/reset?token=' . $token);
		$body = $this->renderTemplate('layouts/email', 'users/reset-password-email', ['link' => $link, 'user' => $user]);

		$this->container['services.postman']->deliver($to, $from, $subject, $body);

		$this->container['messages']->success(['We have sent a email with further instructions']);
		return $this->redirect('/admin/auth/login');
	}

	public function getReset($request) {
		// check token
		$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
		$user = $this->container['services.auth']->verifyToken($token);

		if( ! $user) {
			$this->container['messages']->error(['Invalid reset token']);
			return $this->redirect($this->container['url']->to('/admin/auth/login'));
		}

		$vars['title'] = 'Reset Password';

		$form = new \Forms\Reset([
			'method' => 'post',
			'action' => $this->container['url']->to('/admin/auth/reset?token=' . $token),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());

		$vars['form'] = $form;

		return $this->renderTemplate('layouts/minimal', 'users/reset', $vars);
	}

	public function postReset($request) {
		// check token
		$token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
		$user = $this->container['services.auth']->verifyToken($token);

		if( ! $user) {
			$this->container['messages']->error(['Invalid reset token']);
			return $this->redirect($this->container['url']->to('/admin/auth/login'));
		}

		$form = new \Forms\Reset;
		$input = $form->getFilters();

		// validate input
		$validator = $this->container['validation']->create($input, $form->getRules());

		// check token
		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			return $this->redirect($this->container['url']->to('/admin/auth/reset?token=' . $token));
		}

		$this->container['services.auth']->changePassword($user, $input['password']);

		$this->container['messages']->success(['Your password has been reset']);
		return $this->redirect($this->container['url']->to('/admin/auth/login'));
	}

}
