<?php

namespace Controllers\Installer;

class Install extends Controller {

	public function getIndex() {
		//  We don't need a welcome screen (I think).
		return $this->redirect($this->url('l10n'));

		//$vars['title'] = 'Welcome to Anchor';
		//return $this->renderWith('installer/layout.phtml', 'installer/index.phtml', $vars);
	}

	public function getL10n() {
		$form = new \Forms\Installer\L10n(['method' => 'post', 'action' => $this->url('l10n'), 'autocomplete' => 'off']);
		$form->init();

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/l10n.phtml', $vars);
	}

	public function postL10n() {
		$form = new \Forms\Installer\L10n;

		$input = filter_input_array(INPUT_POST, $form->getFilters());

		$data = $this->session->get('install', []);
		$this->session->put('install', array_merge($data, $input));

		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());

			return $this->redirect($this->url('l10n'));
		}

		return $this->redirect($this->url('database'));
	}

	public function getDatabase() {
		$form = new \Forms\Installer\Database(['method' => 'post', 'action' => $this->url('database'), 'autocomplete' => 'off']);
		$form->init();

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/database.phtml', $vars);
	}

	public function postDatabase() {
		$form = new \Forms\Installer\Database;

		$input = filter_input_array(INPUT_POST, $form->getFilters());

		$data = $this->session->get('install', []);
		$this->session->put('install', array_merge($data, $input));

		$rules = $form->getRules();

		if($input['driver'] == 'mysql') {
			$rules['host'] = ['required'];
			$rules['port'] = ['required'];
			$rules['user'] = ['required'];
		}

		$validator = $this->validation->create($input, $rules);

		try {
			$pdo = $this->installer->connectDatabase($input);
		}
		catch(\PDOException $e) {
			$validator->setInvalid($e->getMessage());
		}
		finally {
			$pdo = null;
		}

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			return $this->redirect($this->url('database'));
		}

		return $this->redirect($this->url('metadata'));
	}

	public function getMetadata() {
		$form = new \Forms\Installer\Metadata(['method' => 'post', 'action' => $this->url('metadata'), 'autocomplete' => 'off']);
		$form->init();

		$form->getElement('site_path')->setValue($this->url('/'));

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/metadata.phtml', $vars);
	}

	public function postMetadata() {
		$form = new \Forms\Installer\Metadata;

		$input = filter_input_array(INPUT_POST, $form->getFilters());

		$data = $this->session->get('install', []);
		$this->session->put('install', array_merge($data, $input));

		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			return $this->redirect($this->url('metadata'));
		}

		return $this->redirect($this->url('account'));
	}

	public function getAccount() {
		$form = new \Forms\Installer\Account(['method' => 'post', 'action' => $this->url('account'), 'autocomplete' => 'off']);
		$form->init();

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['messages'] = $this->session->getFlash('messages', []);

		return $this->renderWith('installer/layout.phtml', 'installer/account.phtml', $vars);
	}

	public function postAccount() {
		$form = new \Forms\Installer\Account;

		$input = filter_input_array(INPUT_POST, $form->getFilters());

		$data = $this->session->get('install', []);
		$this->session->put('install', array_merge($data, $input));

		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->session->putFlash('messages', $validator->getMessages());
			return $this->redirect($this->url('account'));
		}

		$data = $this->session->get('install', []);
		$this->installer->run($data);

		return $this->redirect($this->url('complete'));
	}

	public function getComplete() {
		$vars['title'] = 'Installin\' Anchor CMS';

		$this->session->remove('install');

		return $this->renderWith('installer/layout.phtml', 'installer/finished.phtml', $vars);
	}

}
