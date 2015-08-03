<?php

namespace Controllers\Admin;

class Users extends Backend {

	public function getIndex() {
		$input = filter_var_array($_GET, [
			'page' => FILTER_SANITIZE_NUMBER_INT,
		]);

		$total = $this->users->count();
		$perpage = $this->meta->key('admin_posts_per_page', 10);

		$users = $this->users->sort('real_name', 'asc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$users->skip($offset);
		}

		$paging = new \Paginator('/admin/users', $input['page'], $total, $perpage, $input);

		$vars['title'] = 'Users';
		$vars['users'] = $users->get();
		$vars['paging'] = $paging;

		return $this->renderTemplate('main', ['users/index'], $vars);
	}

	public function getCreate() {
		$form = new \Forms\User(['method' => 'post', 'action' => '/admin/users/save']);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());

		// re-populate submitted data
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = 'Creating a new user';
		$vars['messages'] = $this->messages->get();
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['users/create'], $vars);
	}

	public function postSave() {
		$form = new \Forms\User;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', '/admin/users/create');
		}

		$password = password_hash($input['password'], PASSWORD_DEFAULT);

		$id = $this->users->insert([
			'username' => $input['username'],
			'password' => $password,
			'email' => $input['email'],
			'real_name' => $input['real_name'],
			'bio' => $input['bio'],
			'status' => $input['status'],
			'role' => $input['role'],
		]);

		$this->messages->success('User created');
		return $this->response->withHeader('location', sprintf('/admin/users/%d/edit', $id));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$user = $this->users->where('id', '=', $id)->fetch();

		$form = new \Forms\User([
			'method' => 'post',
			'action' => sprintf('/admin/users/%d/update', $user->id)
		]);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());

		// set default values from post
		$form->setValues($user->toArray());

		// re-populate old input
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $user->real_name);
		$vars['user'] = $user;
		$vars['messages'] = $this->messages->get();
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['users/edit'], $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$user = $this->users->where('id', '=', $id)->fetch();

		$form = new \Forms\User;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$rules = $form->getRules();

		// no password change so no validation
		if(empty($input['password'])) unset($rules['password']);

		$validator = $this->validation->create($input, $rules);

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', sprintf('/admin/users/%d/edit', $user->id));
		}

		$update = [
			'username' => $input['username'],
			'email' => $input['email'],
			'real_name' => $input['real_name'],
			'bio' => $input['bio'],
			'status' => $input['status'],
			'role' => $input['role'],
		];

		// password sent, hash it and add it to update array
		if($input['password']) {
			$update['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
		}

		$this->users->where('id', '=', $user->id)->update($update);

		$this->messages->success('User updated');
		return $this->response->withHeader('location', sprintf('/admin/users/%d/edit', $id));
	}

	public function postDelete() {}

}
