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
		$form->setValues($this->session->getFlash('input', []));

		$vars['title'] = 'Creating a new post';
		$vars['messages'] = $this->messages->get();
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['posts/create'], $vars);
	}

	public function postSave() {
		$form = new \Forms\Post;
		$form->init();

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->sesison->putFlash('input', $input);
			return $this->response->withHeader('location', '/admin/posts/create');
		}

		$id = $this->posts->createFromInput($input);
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
		$form->setValues($this->session->getFlash('input', $post->toArray()));

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

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->validation->create($input, $form->getRules());

		if(false === $validator->isValid()) {
			$this->messages->error($validator->getMessages());
			$this->sesison->putFlash('input', $input);
			return $this->response->withHeader('location', sprintf('/admin/posts/%d/edit', $post->id));
		}

		$id = $this->posts->createFromInput($input);
		$this->messages->success('Post updated');
		return $this->response->withHeader('location', sprintf('/admin/posts/%d/edit', $id));
	}

	public function postDelete() {}

}
