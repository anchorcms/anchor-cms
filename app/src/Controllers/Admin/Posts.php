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

		$total = $this->container['mappers.posts']->filter($input)->count();

		$perpage = $this->container['mappers.meta']->key('admin_posts_per_page', 10);
		$query = $this->container['mappers.posts']->filter($input)->sort('modified', 'desc')->take($perpage);

		if($input['page']) {
			$offset = ($input['page'] - 1) * $perpage;
			$query->skip($offset);
		}

		$posts = $query->get();

		$paging = new \Paginator($this->container['url']->to('/admin/posts'), $input['page'], $total, $perpage, $input);

		$vars['title'] = 'Posts';
		$vars['posts'] = $posts;
		$vars['paging'] = $paging;
		$vars['categories'] = $this->container['mappers.categories']->allPublished();
		$vars['statuses'] = ['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'];
		$vars['filters'] = $input;

		/*
		$items = [];
		$this->events->trigger('admin_menu', & $items);
		$vars['plugins'] = $items;
		*/

		return $this->renderTemplate('layouts/default', 'posts/index', $vars);
	}

	public function getCreate() {
		$form = new \Forms\Post([
			'method' => 'post',
			'action' => $this->container['url']->to('/admin/posts/save'),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());
		$form->getElement('category')->setOptions($this->container['mappers.categories']->dropdownOptions());

		// append custom fields
		$this->container['services.customFields']->appendFields($form, 'post');

		// re-populate submitted data
		$form->setValues($this->container['session']->getFlash('input', []));

		$vars['title'] = 'Creating a new post';
		$vars['form'] = $form;

		return $this->renderTemplate('layouts/default', 'posts/create', $vars);
	}

	public function postSave($request) {
		$form = new \Forms\Post;
		$form->init();

		// append custom fields
		$this->container['services.customFields']->appendFields($form, 'post');

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->container['validation']->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', $input);
			return $this->redirect($this->container['url']->to('/admin/posts/create'));
		}

		$now = date('Y-m-d H:i:s');
		$slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);
		$html = $this->container['markdown']->convertToHtml($input['content']);
		$user = $this->container['session']->get('user');

		$id = $this->container['mappers.posts']->insert([
			'author' => $user,
			'category' => $input['category'],
			'status' => $input['status'],

			'created' => $now,
			'modified' => $now,
			'published' => $input['published'] ?: $now,

			'title' => $input['title'],
			'slug' => $slug,

			'content' => $input['content'],
			'html' => $html,
		]);

		// save custom fields
		$this->container['services.customFields']->saveFields($request, $input, 'post', $id);

		$this->container['messages']->success('Post created');
		return $this->redirect($this->container['url']->to(sprintf('/admin/posts/%d/edit', $id)));
	}

	public function getEdit($request) {
		$id = $request->getAttribute('id');
		$post = $this->container['mappers.posts']->where('id', '=', $id)->fetch();

		$form = new \Forms\Post([
			'method' => 'post',
			'action' => $this->container['url']->to(sprintf('/admin/posts/%d/update', $post->id)),
		]);
		$form->init();
		$form->getElement('_token')->setValue($this->container['csrf']->token());
		$form->getElement('category')->setOptions($this->container['mappers.categories']->dropdownOptions());

		// set default values from post
		$form->setValues($post->toArray());

		// append custom fields
		$this->container['services.customFields']->appendFields($form, 'post');

		// get custom field values
		$form->setValues($this->container['services.customFields']->getFieldValues('post', $id));

		// re-populate old input
		$form->setValues($this->container['session']->getFlash('input', []));

		$vars['title'] = sprintf('Editing &ldquo;%s&rdquo;', $post->title);
		$vars['form'] = $form;
		$vars['post'] = $post;

		return $this->renderTemplate('layouts/default', 'posts/edit', $vars);
	}

	public function postUpdate($request) {
		$id = $request->getAttribute('id');
		$post = $this->container['mappers.posts']->where('id', '=', $id)->fetch();

		$form = new \Forms\Post;
		$form->init();

		// append custom fields
		$this->container['services.customFields']->appendFields($form, 'post');

		$input = filter_input_array(INPUT_POST, $form->getFilters());
		$validator = $this->container['validation']->create($input, $form->getRules());

		$validator->addRule(new \Forms\ValidateToken($this->container['csrf']->token()), '_token');

		if(false === $validator->isValid()) {
			$this->container['messages']->error($validator->getMessages());
			$this->container['session']->putFlash('input', $input);
			return $this->redirect($this->container['url']->to(sprintf('/admin/posts/%d/edit', $post->id)));
		}

		$now = date('Y-m-d H:i:s');
		$slug = $this->container['slugify']->slug($input['slug'] ?: $input['title']);
		$html = $this->container['markdown']->convertToHtml($input['content']);

		$this->container['mappers.posts']->where('id', '=', $post->id)->update([
			'category' => $input['category'],
			'status' => $input['status'],

			'modified' => $now,
			'published' => $input['published'] ?: $now,

			'title' => $input['title'],
			'slug' => strtolower($slug),

			'content' => $input['content'],
			'html' => $html,
		]);

		// update custom fields
		$this->container['services.customFields']->saveFields($request, $input, 'post', $id);

		$this->container['messages']->success('Post updated');
		return $this->redirect($this->container['url']->to(sprintf('/admin/posts/%d/edit', $id)));
	}

	public function getDelete($request) {
		$id = $request->getAttribute('id');
		$post = $this->container['mappers.posts']->where('id', '=', $id)->fetch();

		if( ! $post) {
			return $this->redirect($this->container['url']->to('/admin/posts'));
		}

		$this->container['mappers.posts']->where('id', '=', $post->id)->delete();
		$this->container['mappers.postmeta']->where('post', '=', $post->id)->delete();

		$this->container['messages']->success('Post deleted');
		return $this->redirect($this->container['url']->to('/admin/posts'));
	}

}
