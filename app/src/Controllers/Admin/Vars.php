<?php

namespace Controllers\Admin;

class Vars extends Backend {

	public function getIndex() {
		$input = filter_var_array($_GET, [
			'page' => FILTER_SANITIZE_NUMBER_INT,
		]);

		$total = $this->meta->where('key', 'LIKE', 'custom_%')->count();

		$perpage = $this->meta->key('admin_posts_per_page', 10);
		$meta = $this->meta->where('key', 'LIKE', 'custom_%')->sort('key', 'asc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$meta->skip($offset);
		}

		$paging = new \Paginator('/admin/vars', $input['page'], $total, $perpage, $input);

		$vars['title'] = 'Custom Variables';
		$vars['metadata'] = $meta->get();
		$vars['messages'] = $this->messages->render($this->getViewPath().'/messages.phtml');
		$vars['paging'] = $paging;

		return $this->renderTemplate('main', ['vars/index'], $vars);
	}

	public function getCreate() {
		$form = new \Forms\CustomVars(['method' => 'post', 'action' => '/admin/vars/save']);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());

		// re-populate submitted data
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = 'Creating a new custom variable';
		$vars['messages'] = $this->messages->render($this->getViewPath().'/messages.phtml');
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['vars/create'], $vars);
	}

	public function postSave() {
		$form = new \Forms\CustomVars;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', '/admin/vars/create');
		}

		$this->meta->insert([
			'key' => 'custom_'.$input['key'],
			'value' => $input['value'],
		]);

		$this->messages->success('Custom variable created');
		return $this->response->withHeader('location', '/admin/vars');
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$meta = $this->meta->where('key', '=', $id)->fetch();

		$form = new \Forms\CustomVars([
			'method' => 'post',
			'action' => sprintf('/admin/vars/%s/update', $meta->key)
		]);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());

		// cannot change key
		$form->getElement('key')->setAttribute('readonly', 'readonly');

		// set default values from post
		$form->setValues($meta->toArray());

		// re-populate old input
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $meta->key);
		$vars['messages'] = $this->messages->render($this->getViewPath().'/messages.phtml');
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['vars/edit'], $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$meta = $this->meta->where('key', '=', $id)->fetch();

		$form = new \Forms\CustomVars;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', sprintf('/admin/vars/%d/edit', $meta->key));
		}

		$this->meta->where('key', '=', $meta->key)->update([
			'value' => $input['value'],
		]);

		$this->messages->success('Custom variable updated');
		return $this->response->withHeader('location', '/admin/vars');
	}

	public function postDelete() {}

}
