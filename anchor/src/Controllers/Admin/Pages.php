<?php

namespace Controllers\Admin;

class Pages extends Backend {

	public function getIndex($request) {
		$input = filter_var_array($request->getQueryParams(), [
			'page' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_STRING,
			'search' => FILTER_SANITIZE_STRING,
		]);

		$total = $this->pages->filter($input)->count();

		$perpage = $this->meta->key('admin_posts_per_page', 10);
		$pages = $this->pages->filter($input)->sort('title', 'asc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$pages->skip($offset);
		}

		$paging = new \Paginator($this->url->to('/admin/pages'), $input['page'], $total, $perpage, $input);

		$vars['title'] = 'Pages';
		$vars['pages'] = $pages->get();
		$vars['paging'] = $paging;
		$vars['statuses'] = ['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'];
		$vars['filters'] = $input;

		return $this->renderTemplate('layouts/default', 'pages/index', $vars);
	}

	public function getCreate() {
		$form = new \Forms\Page([
			'method' => 'post',
			'action' => $this->url->to('/admin/pages/save'),
		]);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());
		$form->getElement('parent')->setOptions($this->pages->dropdownOptions());

		// append custom fields
		$this->customFields->appendFields($form, 'page');

		// re-populate submitted data
		$form->setValues($this->session->getFlash('input', []));

		$element = $form->getElement('show_in_menu');

		if($element->getValue()) {
			$element->setChecked();
		}

		$element->setValue(1);

		$vars['title'] = 'Creating a new page';
		$vars['form'] = $form;

		return $this->renderTemplate('layouts/default', 'pages/create', $vars);
	}

	public function postSave() {
		$form = new \Forms\Page;
		$form->init();

		// append custom fields
		$this->customFields->appendFields($form, 'page');

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', $this->url->to('/admin/pages/create'));
		}

		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);
		$html = $this->markdown->parse($input['content']);

		$id = $this->pages->insert([
			'parent' => $input['parent'],
			'slug' => $slug,
			'name' => $input['name'] ?: $input['title'],
			'title' => $input['title'],
			'content' => $input['content'],
			'html' => $html,
			'status' => $input['status'],
			'redirect' => $input['redirect'],
			'show_in_menu' => $input['show_in_menu'] ? 1 : 0,
			'menu_order' => $input['menu_order'],
		]);

		// save custom fields
		$this->customFields->saveFields($request, $input, 'page', $id);

		$this->messages->success('Page created');
		return $this->response->withHeader('location', $this->url->to(sprintf('/admin/pages/%d/edit', $id)));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$page= $this->pages->where('id', '=', $id)->fetch();

		$form = new \Forms\Page([
			'method' => 'post',
			'action' => $this->url->to(sprintf('/admin/pages/%d/update', $page->id)),
		]);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());
		$form->getElement('parent')->setOptions($this->pages->dropdownOptions([0 => 'None']));

		// set default values from post
		$form->setValues($page->toArray());

		// append custom fields
		$this->customFields->appendFields($form, 'page');

		// get custom field values
		$form->setValues($this->customFields->getFieldValues('page', $id));

		// re-populate old input
		$form->setValues($this->session->getFlash('input', []));

		$element = $form->getElement('show_in_menu');

		if($element->getValue()) {
			$element->setChecked();
		}

		$element->setValue(1);

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $page->title);
		$vars['page'] = $page;
		$vars['form'] = $form;

		return $this->renderTemplate('layouts/default', 'pages/edit', $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$page = $this->pages->where('id', '=', $id)->fetch();

		$form = new \Forms\Page;
		$form->init();

		// append custom fields
		$this->customFields->appendFields($form, 'page');

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', $this->url->to(sprintf('/admin/pages/%d/edit', $page->id)));
		}

		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);
		$html = $this->markdown->parse($input['content']);

		$this->pages->where('id', '=', $page->id)->update([
			'parent' => $input['parent'],
			'slug' => $slug,
			'name' => $input['name'] ?: $input['title'],
			'title' => $input['title'],
			'content' => $input['content'],
			'html' => $html,
			'status' => $input['status'],
			'redirect' => $input['redirect'],
			'show_in_menu' => $input['show_in_menu'] ? 1 : 0,
			'menu_order' => $input['menu_order'],
		]);

		// update custom fields
		$this->customFields->saveFields($request, $input, 'post', $id);

		$this->messages->success('Page updated');
		return $this->response->withHeader('location', $this->url->to(sprintf('/admin/pages/%d/edit', $id)));
	}

}
