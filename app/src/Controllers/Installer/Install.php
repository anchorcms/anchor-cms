<?php

namespace Controllers\Installer;

use Pimple\Container;
use Controllers\AbstractController;

class Install extends AbstractController {

	public function __construct(Container $container) {
		$this->setContainer($container);
		$this->view->setPath($this->paths['views']);
	}

	protected function renderTemplate($layout, $template, array $vars = []) {
		$vars['messages'] = $this->messages->render();
		$vars['uri'] = $this->request->getUri();
		$vars['body'] = $this->view->render($template, $vars);

		return $this->view->render($layout, $vars);
	}

	public function getIndex() {
		//  We don't need a welcome screen (I think).
		return $this->redirect('/l10n');

		//$vars['title'] = 'Welcome to Anchor';
		//return $this->renderWith('installer/layout.phtml', 'installer/index.phtml', $vars);
	}

	public function getL10n() {
		$form = new \Forms\Installer\L10n([
			'method' => 'post',
			'action' => '/l10n',
			'autocomplete' => 'off',
		]);
		$form->init();

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;

		return $this->renderTemplate('installer/layout', 'installer/l10n', $vars);
	}

	public function postL10n() {
		$form = new \Forms\Installer\L10n;

		$input = filter_input_array(INPUT_POST, $form->getFilters());

		$data = $this->session->get('install', []);
		$this->session->put('install', array_merge($data, $input));

		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());

			return $this->redirect('/l10n');
		}

		return $this->redirect('/database');
	}

	public function getDatabase() {
		$form = new \Forms\Installer\Database([
			'method' => 'post',
			'action' => '/database',
			'autocomplete' => 'off',
		]);
		$form->init();

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['backUrl'] = '/l10n';

		return $this->renderTemplate('installer/layout', 'installer/database', $vars);
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
			$this->messages->error($validator->getMessages());
			return $this->redirect('/database');
		}

		return $this->redirect('/metadata');
	}

	public function getMetadata() {
		$form = new \Forms\Installer\Metadata([
			'method' => 'post',
			'action' => '/metadata',
			'autocomplete' => 'off',
		]);
		$form->init();

		$form->getElement('site_path')->setValue('/');

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['backUrl'] = '/database';

		return $this->renderTemplate('installer/layout', 'installer/metadata', $vars);
	}

	public function postMetadata() {
		$form = new \Forms\Installer\Metadata;

		$input = filter_input_array(INPUT_POST, $form->getFilters());

		$data = $this->session->get('install', []);
		$this->session->put('install', array_merge($data, $input));

		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			return $this->redirect('/metadata');
		}

		return $this->redirect('/account');
	}

	public function getAccount() {
		$form = new \Forms\Installer\Account([
			'method' => 'post',
			'action' => '/account',
			'autocomplete' => 'off',
		]);
		$form->init();

		// populate from session
		$data = $this->session->get('install', []);
		$form->setValues($data);

		$vars['title'] = 'Installin\' Anchor CMS';
		$vars['form'] = $form;
		$vars['backUrl'] = '/metadata';

		return $this->renderTemplate('installer/layout', 'installer/account', $vars);
	}

	public function postAccount() {
		$form = new \Forms\Installer\Account;

		$input = filter_input_array(INPUT_POST, $form->getFilters());

		$data = $this->session->get('install', []);
		$this->session->put('install', array_merge($data, $input));

		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			return $this->redirect('/account');
		}

		$data = $this->session->get('install', []);
		$this->installer->run($data);

		return $this->redirect('/complete');
	}

	public function getComplete() {
		$vars['title'] = 'Installin\' Anchor CMS';

		$this->session->remove('install');

		return $this->renderTemplate('installer/layout', 'installer/finished', $vars);
	}

}
