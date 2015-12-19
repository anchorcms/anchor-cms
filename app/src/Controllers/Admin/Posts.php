<?php

namespace Controllers\Admin;

class Posts extends Backend {

	public function getIndex($request) {
		$input = filter_var_array($request->getQueryParams(), [
			'page' => FILTER_SANITIZE_NUMBER_INT,
			'category' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_STRING,
			'search' => FILTER_SANITIZE_STRING,
		]);

		$total = $this->posts->filter($input)->count();

		$perpage = $this->meta->key('admin_posts_per_page', 10);
		$query = $this->posts->filter($input)->sort('modified', 'desc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$query->skip($offset);
		}

		$posts = $query->get();

		$paging = new \Paginator($this->url->to('/admin/posts'), $input['page'], $total, $perpage, $input);

		$vars['title'] = sprintf('Posts - %s', $this->meta->key('sitename'));
		$vars['posts'] = $posts;
		$vars['paging'] = $paging;
		$vars['categories'] = $this->categories->get();
		$vars['statuses'] = ['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'];
		$vars['filters'] = $input;

		/*
		$items = [];
		$this->events->trigger('admin_menu', & $items);
		$vars['plugins'] = $items;
		*/

		return $this->renderTemplate('layout', 'posts/index', $vars);
	}

	public function getCreate() {
		$form = new \Forms\Post([
			'method' => 'post',
			'action' => $this->url->to('/admin/posts/save'),
		]);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());
		$form->getElement('category')->setOptions($this->categories->dropdownOptions());

		// append custom fields
		$this->customFields->appendFields($form, 'post');

		// re-populate submitted data
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = 'Creating a new post';
		$vars['form'] = $form;

		return $this->renderTemplate('layout', 'posts/create', $vars);
	}

	public function postSave($request) {
		$form = new \Forms\Post;
		$form->init();

		// append custom fields
		$this->customFields->appendFields($form, 'post');

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', $this->url->to('/admin/posts/create'));
		}

		$now = date('Y-m-d H:i:s');
		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);
		$html = $this->markdown->parse($input['content']);
		$user = $this->session->get('user');

		$id = $this->posts->insert([
			'author' => $user,
			'category' => $input['category'],
			'status' => $input['status'],

			'created' => $now,
			'modified' => $now,

			'title' => $input['title'],
			'slug' => $slug,

			'content' => $input['content'],
			'html' => $html,
		]);

		// save custom fields
		$this->customFields->saveFields($request, $input, 'post', $id);

		$this->messages->success('Post created');
		return $this->response->withHeader('location', $this->url->to(sprintf('/admin/posts/%d/edit', $id)));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$post = $this->posts->where('id', '=', $id)->fetch();

		$form = new \Forms\Post([
			'method' => 'post',
			'action' => $this->url->to(sprintf('/admin/posts/%d/update', $post->id)),
		]);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());
		$form->getElement('category')->setOptions($this->categories->dropdownOptions());

		// set default values from post
		$form->setValues($post->toArray());

		// append custom fields
		$this->customFields->appendFields($form, 'post');

		// get custom field values
		$form->setValues($this->customFields->getFieldValues('post', $id));

		// re-populate old input
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $post->title);
		$vars['form'] = $form;
		$vars['current'] = $post;
		$vars['posts'] = $this->posts->take(5)->sort('modified', 'desc')->where('id', '<>', $post->id)->get();

		return $this->renderTemplate('layout', 'posts/edit', $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$post = $this->posts->where('id', '=', $id)->fetch();

		$form = new \Forms\Post;
		$form->init();

		// append custom fields
		$this->customFields->appendFields($form, 'post');

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->csrf->token()), 'token');

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->session->putFlash('input', $input);
			return $this->response->withHeader('location', $this->url->to(sprintf('/admin/posts/%d/edit', $post->id)));
		}

		$now = date('Y-m-d H:i:s');
		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);
		$html = $this->markdown->parse($input['content']);

		$this->posts->where('id', '=', $post->id)->update([
			'category' => $input['category'],
			'status' => $input['status'],

			'modified' => $now,

			'title' => $input['title'],
			'slug' => strtolower($slug),

			'content' => $input['content'],
			'html' => $html,
		]);

		// update custom fields
		$this->customFields->saveFields($request, $input, 'post', $id);

		$this->messages->success('Post updated');
		return $this->response->withHeader('location', $this->url->to(sprintf('/admin/posts/%d/edit', $id)));
	}

}
