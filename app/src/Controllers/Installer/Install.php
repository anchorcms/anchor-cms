<?php

namespace Controllers\Installer;

class Install extends Controller {

	public function getIndex() {
		$vars['title'] = 'Welcome to Anchor';

		return $this->renderWith('installer/layout.phtml', 'installer/index.phtml', $vars);
	}

	public function getL10n() {
		$form = new \Forms\Installer\L10n(['method' => 'post', 'action' => $this->url('index.php?installer=l10n'), 'autocomplete' => 'off']);
		$form->init();

		// populate from session
		$data = $this->session->get('data', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/l10n.phtml', $vars);
	}

	public function postL10n() {
		$input = filter_input_array(INPUT_POST, [
			'lang' => FILTER_SANITIZE_STRING,
			'timezone' => FILTER_SANITIZE_STRING,
		]);

		$data = $this->session->get('data', []);
		$this->session->put('data', array_merge($data, $input));

		$rules = [
			'lang' => ['required'],
			'timezone' => ['required'],
		];

		$validator = $this->validation->create($input, $rules);

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			return $this->redirect($this->url('index.php?installer=l10n'));
		}

		$this->redirect($this->url('index.php?installer=database'));
	}

	public function getDatabase() {
		$form = new \Forms\Installer\Database(['method' => 'post', 'action' => $this->url('index.php?installer=database'), 'autocomplete' => 'off']);
		$form->init();

		// populate from session
		$data = $this->session->get('data', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/database.phtml', $vars);
	}

	public function postDatabase() {
		$input = filter_input_array(INPUT_POST, [
			'driver' => FILTER_SANITIZE_STRING,
			'host' => FILTER_SANITIZE_STRING,
			'port' => FILTER_SANITIZE_STRING,
			'user' => FILTER_SANITIZE_STRING,
			'pass' => FILTER_UNSAFE_RAW,
			'dbname' => FILTER_SANITIZE_STRING,
			'prefix' => FILTER_SANITIZE_STRING,
		]);

		$data = $this->session->get('data', []);
		$this->session->put('data', array_merge($data, $input));

		$rules = [
			'driver' => ['required'],
			'dbname' => ['required'],
		];

		if($input['driver'] == 'mysql') {
			$rules['host'] = ['required'];
			$rules['port'] = ['required'];
			$rules['user'] = ['required'];
		}

		$validator = $this->validation->create($input, $rules);

		// test connection
		if($input['driver'] == 'mysql') {
			$dns = $this->installer->buildConnectionDns($input);
			$result = $this->installer->testConnection($dns, $input['user'], $input['pass']);

			if(false === $result) {
				$validator->setInvalid($this->installer->getConnectionError());
			}
		}

		// test file
		if($input['driver'] == 'sqlite') {
			$path = __DIR__ . '/../../../' . $input['dbname'];

			// try creating it if it doesnt exist
			if(false === is_file($path)) {
				touch($path);
			}

			if(false === is_file($path)) {
				$validator->setInvalid('Could not create database file.');
			}
		}

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			return $this->redirect($this->url('index.php?installer=database'));
		}

		$this->redirect($this->url('index.php?installer=metadata'));
	}

	public function getMetadata() {
		$form = new \Forms\Installer\Metadata(['method' => 'post', 'action' => $this->url('index.php?installer=metadata'), 'autocomplete' => 'off']);
		$form->init();

		$form->getElement('site_path')->setValue($this->url('/'));

		// populate from session
		$data = $this->session->get('data', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/metadata.phtml', $vars);
	}

	public function postMetadata() {
		$input = filter_input_array(INPUT_POST, [
			'site_name' => FILTER_SANITIZE_STRING,
			'site_description' => FILTER_SANITIZE_STRING,
			'site_path' => FILTER_SANITIZE_STRING,
		]);

		$data = $this->session->get('data', []);
		$this->session->put('data', array_merge($data, $input));

		$rules = [
			'site_name' => ['required'],
			'site_description' => ['required'],
			'site_path' => ['required'],
		];

		$validator = $this->validation->create($input, $rules);

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			return $this->redirect($this->url('index.php?installer=metadata'));
		}

		$this->redirect($this->url('index.php?installer=account'));
	}

	public function getAccount() {
		$form = new \Forms\Installer\Account(['method' => 'post', 'action' => $this->url('index.php?installer=account'), 'autocomplete' => 'off']);
		$form->init();

		// populate from session
		$data = $this->session->get('data', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/account.phtml', $vars);
	}

	public function postAccount() {
		$input = filter_input_array(INPUT_POST, [
			'username' => FILTER_SANITIZE_STRING,
			'email' => FILTER_SANITIZE_STRING,
			'password' => FILTER_UNSAFE_RAW,
		]);

		$data = $this->session->get('data', []);
		$this->session->put('data', array_merge($data, $input));

		$rules = [
			'username' => ['required'],
			'email' => ['required', 'email'],
			'password' => ['required'],
		];

		$validator = $this->validation->create($input, $rules);

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			return $this->redirect($this->url('index.php?installer=account'));
		}

		$data = $this->session->get('data', []);
		$this->installer->run($data);

		$this->redirect($this->url('index.php?installer=complete'));
	}

	public function getComplete() {
		$vars['title'] = 'Installin\' Anchor CMS';

		$this->session->remove('data');

		return $this->renderWith('installer/layout.phtml', 'installer/finished.phtml', $vars);
	}

}
