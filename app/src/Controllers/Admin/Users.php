<?php

namespace Controllers\Admin;

class Users extends Backend {

	public function getIndex() {
		$input = filter_var_array($_GET, [
			'page' => FILTER_SANITIZE_NUMBER_INT,
		]);

		$total = $this->container['mappers.users']->count();
		$perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);

		$users = $this->container['mappers.users']->sort('real_name', 'asc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$users->skip($offset);
		}

		$paging = new \Paginator($this->container['url']->to('/admin/users'), $input['page'], $total, $perpage, $input);

		$vars['title'] = 'Users';
		$vars['users'] = $users->get();
		$vars['paging'] = $paging;

		return $this->renderTemplate('layouts/default', 'users/index', $vars);
	}

	public function getCreate() {
		$form = new \Forms\User([
			'method' => 'post',
			'action' => $this->container['url']->to('/admin/users/save'),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());
		$form->setValues($this->container['session']->getFlash('input', []));

		$vars['title'] = 'Creating a new user';
		$vars['form'] = $form;

		return $this->renderTemplate('layouts/default', 'users/create', $vars);
	}

	public function postSave() {
		$form = new \Forms\User;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->container['validation']->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if($validator->isValid()) {
			$query = $this->container['mappers.users']
				->where('username', '=', $input['username']);

			if($query->count()) {
				$validator->setInvalid('Username already taken');
			}

			$query = $this->container['mappers.users']
				->where('email', '=', $input['email']);

			if($query->count()) {
				$validator->setInvalid('Email address already in use');
			}
		}

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', $input);
			return $this->redirect($this->container['url']->to('/admin/users/create'));
		}

		$password = $this->container['services.auth']->hashPassword($input['password']);

		$id = $this->container['mappers.users']->insert([
			'username' => $input['username'],
			'password' => $password,
			'email' => $input['email'],
			'name' => $input['name'],
			'bio' => $input['bio'],
			'status' => $input['status'],
			'role' => $input['role'],
			'token' => '',
		]);

		$this->container['messages']->success('User created');
		return $this->redirect($this->container['url']->to(sprintf('/admin/users/%d/edit', $id)));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$user = $this->container['mappers.users']->where('id', '=', $id)->fetch();

		$form = new \Forms\User([
			'method' => 'post',
			'action' => $this->container['url']->to(sprintf('/admin/users/%d/update', $user->id)),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());

		// set default values from post
		$form->setValues($user->toArray());

		// re-populate old input
		$form->setValues($this->container['session']->getFlash('input', []));

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $user->name);
		$vars['user'] = $user;
		$vars['form'] = $form;

		return $this->renderTemplate('layouts/default', 'users/edit', $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$user = $this->container['mappers.users']->where('id', '=', $id)->fetch();

		$form = new \Forms\User;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$rules = $form->getRules();

		// no password change so no validation
		if(empty($input['password'])) unset($rules['password']);

		$validator = $this->container['validation']->create($input, $rules);

		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', $input);
			return $this->redirect($this->container['url']->to(sprintf('/admin/users/%d/edit', $user->id)));
		}

		$update = [
			'username' => $input['username'],
			'email' => $input['email'],
			'name' => $input['name'],
			'bio' => $input['bio'],
			'status' => $input['status'],
			'role' => $input['role'],
		];

		// password set, hash it and add it to update array
		if($input['password']) {
			$update['password'] = $this->container['services.auth']->hashPassword($input['password']);
		}

		$this->container['mappers.users']->where('id', '=', $user->id)->update($update);

		$this->container['messages']->success('User updated');
		return $this->redirect($this->container['url']->to(sprintf('/admin/users/%d/edit', $id)));
	}

}
