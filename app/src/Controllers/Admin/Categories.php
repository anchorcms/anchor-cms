<?php

namespace Anchorcms\Controllers\Admin;

class Categories extends Backend {

	public function getIndex() {
		$input = filter_var_array($_GET, [
			'page' => FILTER_SANITIZE_NUMBER_INT,
		]);

		$total = $this->container['mappers.categories']->count();

		$perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
		$categories = $this->container['mappers.categories']->sort('title', 'asc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$categories->skip($offset);
		}

		$paging = new \Paginator($this->container['url']->to('/admin/categories'), $input['page'], $total, $perpage, $input);

		$vars['title'] = 'Categories';
		$vars['categories'] = $categories->get();
		$vars['paging'] = $paging;

		return $this->renderTemplate('layouts/default', 'categories/index', $vars);
	}

	public function getCreate() {
		$form = new \Forms\Category([
			'method' => 'post',
			'action' => $this->container['url']->to('/admin/categories/save'),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());

		// re-populate submitted data
		$form->setValues($this->container['session']->getFlash('input', []));

		$vars['title'] = 'Creating a new category';
		$vars['form'] = $form;

		return $this->renderTemplate('layouts/default', 'categories/create', $vars);
	}

	public function postSave() {
		$form = new \Forms\Category;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->container['validation']->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', $input);
			return $this->redirect($this->container['url']->to('/admin/categories/create'));
		}

		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);

		$id = $this->container['mappers.categories']->insert([
			'title' => $input['title'],
			'slug' => strtolower($slug),
			'description' => $input['description'],
		]);

		$this->container['messages']->success('Category created');
		return $this->redirect($this->container['url']->to(sprintf('/admin/categories/%d/edit', $id)));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$category = $this->container['mappers.categories']->where('id', '=', $id)->fetch();

		$form = new \Forms\Category([
			'method' => 'post',
			'action' => $this->container['url']->to(sprintf('/admin/categories/%d/update', $category->id))
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());

		// set default values from post
		$form->setValues($category->toArray());

		// re-populate old input
		$form->setValues($this->container['session']->getFlash('input', []));

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $category->title);
		$vars['category'] = $category;
		$vars['form'] = $form;

		return $this->renderTemplate('layouts/default', 'categories/edit', $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$category = $this->container['mappers.categories']->where('id', '=', $id)->fetch();

		$form = new \Forms\Category;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->container['validation']->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', $input);
			return $this->redirect($this->container['url']->to(sprintf('/admin/categories/%d/edit', $post->id)));
		}

		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);

		$this->container['mappers.categories']->where('id', '=', $category->id)->update([
			'title' => $input['title'],
			'slug' => strtolower($slug),
			'description' => $input['description'],
		]);

		$this->container['messages']->success('Category updated');
		return $this->redirect($this->container['url']->to(sprintf('/admin/categories/%d/edit', $id)));
	}

}
