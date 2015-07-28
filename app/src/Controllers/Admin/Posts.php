<?php

namespace Controllers\Admin;

class Posts extends Backend {

	public function getIndex() {
		// start query
		$posts = $this->posts->sort('created', 'desc');
		$url = [];

		// apply filters from request
		$input = filter_input_array(INPUT_GET, [
			'page' => FILTER_SANITIZE_NUMBER_INT,
			'category' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_STRING,
		]);

		if($input['category']) {
			$posts->where('category', '=', $input['category']);
			$url['category'] = $input['category'];
		}

		if($input['status']) {
			$posts->where('status', '=', $input['status']);
			$url['status'] = $input['status'];
		}

		$query = clone $posts;
		$total = $query->count();

		$perpage = $this->meta->key('admin_posts_per_page', 10);
		$posts->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$posts->skip($offset);
		}

		$url['page'] = '';

		$paging = new \Paginator($input['page'], $total, $perpage, '/admin/posts?'.http_build_query($url));

		$vars['title'] = 'Posts';
		$vars['posts'] = $posts->get();
		$vars['paging'] = $paging;
		$vars['categories'] = $this->categories->get();

		return $this->renderTemplate('main', ['posts/index'], $vars);
	}

	public function getCreate() {
		$form = new \Forms\Post(['method' => 'post', 'action' => '/admin/posts/save']);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());
		$form->getElement('category')->setOptions($this->categories->dropdownOptions());

		// append custom fields
		$this->customFields->appendFields($form, 'post');

		// re-populate submitted data
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = 'Creating a new post';
		$vars['messages'] = $this->messages->get();
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['posts/create'], $vars);
	}

	public function postSave() {
		$form = new \Forms\Post;
		$form->init();

		// append custom fields
		$this->customFields->appendFields($form, 'post');

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->sesison->putFlash('input', $input);
			return $this->response->withHeader('location', '/admin/posts/create');
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
		$this->customFields->saveFields($input, 'post', $id);

		$this->messages->success('Post created');
		return $this->response->withHeader('location', sprintf('/admin/posts/%d/edit', $id));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$post = $this->posts->where('id', '=', $id)->fetch();

		$form = new \Forms\Post([
			'method' => 'post',
			'action' => sprintf('/admin/posts/%d/update', $post->id)
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
		$vars['post'] = $post;
		$vars['messages'] = $this->messages->get();
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['posts/edit'], $vars);
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

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->sesison->putFlash('input', $input);
			return $this->response->withHeader('location', sprintf('/admin/posts/%d/edit', $post->id));
		}

		$now = date('Y-m-d H:i:s');
		$slug = preg_replace('#\s+#', '-', $input['slug'] ?: $input['title']);
		$html = $this->markdown->parse($input['content']);

		$this->posts->where('id', '=', $post->id)->update([
			'category' => $input['category'],
			'status' => $input['status'],

			'modified' => $now,

			'title' => $input['title'],
			'slug' => $slug,

			'content' => $input['content'],
			'html' => $html,
		]);

		// update custom fields
		$this->customFields->updateFields($input, 'post', $id);

		$this->messages->success('Post updated');
		return $this->response->withHeader('location', sprintf('/admin/posts/%d/edit', $id));
	}

	public function postDelete() {}

}
