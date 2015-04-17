<?php

namespace Controllers\Admin;

class Posts extends Backend {

	public function index() {
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

		return $this->renderTemplate('main', ['posts/index'], $vars);
	}

	public function create() {}

	public function save() {}

	public function edit($id) {
		$post = $this->posts->id($id);

		$form = new \Forms\Post(['method' => 'post', 'action' => '/admin/posts/'.$post->id.'/update']);
		$form->init();
		$form->getElement('token')->setValue($this->csrf->token());
		$form->setValues((array) $post);

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $post->title);
		$vars['post'] = $post;
		$vars['form'] = $form;

		return $this->renderTemplate('main', ['posts/edit'], $vars);
	}

	public function update() {}

	public function delete() {}

}
