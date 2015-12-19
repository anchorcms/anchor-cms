<?php

namespace Controllers\Admin;

class Categories extends Backend {

	public function getIndex() {
		$input = filter_var_array($_GET, [
			'page' => FILTER_SANITIZE_NUMBER_INT,
		]);

		$total = $this->categories->count();

		$perpage = $this->meta->key('admin_posts_per_page', 10);
		$categories = $this->categories->sort('title', 'asc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$categories->skip($offset);
		}

		$paging = new \Paginator('/admin/categories', $input['page'], $total, $perpage, $input);

		$vars['title'] = 'Categories';
		$vars['categories'] = $categories->get();
		$vars['paging'] = $paging;

		return $this->renderTemplate('layout', 'categories/index', $vars);
	}

	public function getCreate() {
		$form = new \Forms\Category(['method' => 'post', 'action' => '/admin/categories/save']);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());

		// re-populate submitted data
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = 'Creating a new category';
		$vars['form'] = $form;

		return $this->renderTemplate('layout', 'categories/create', $vars);
	}

	public function postSave() {
		$form = new \Forms\Category;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', '/admin/categories/create');
		}

		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);

		$id = $this->categories->insert([
			'title' => $input['title'],
			'slug' => strtolower($slug),
			'description' => $input['description'],
		]);

		$this->messages->success('Category created');
		return $this->response->withHeader('location', sprintf('/admin/categories/%d/edit', $id));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$category = $this->categories->where('id', '=', $id)->fetch();

		$form = new \Forms\Category([
			'method' => 'post',
			'action' => sprintf('/admin/categories/%d/update', $category->id)
		]);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());

		// set default values from post
		$form->setValues($category->toArray());

		// re-populate old input
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $category->title);
		$vars['category'] = $category;
		$vars['form'] = $form;

		return $this->renderTemplate('layout', 'categories/edit', $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$category = $this->categories->where('id', '=', $id)->fetch();

		$form = new \Forms\Category;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', sprintf('/admin/categories/%d/edit', $post->id));
		}

		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);

		$this->categories->where('id', '=', $category->id)->update([
			'title' => $input['title'],
			'slug' => strtolower($slug),
			'description' => $input['description'],
		]);

		$this->messages->success('Category updated');
		return $this->response->withHeader('location', sprintf('/admin/categories/%d/edit', $id));
	}

}
