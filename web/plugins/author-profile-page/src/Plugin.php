<?php

namespace Plugins;

class AuthorProfilePage {

	protected $name = 'Author';

	protected $desc = 'Creates a author profile page';

	public function init($events) {
		$events->listen('routing', [$this, 'routing']);
	}

	public function routing($routes) {
		$routes->prepend('/author/:author', function($request) {
			return $this->view($request);
		});
	}

	public function view($request) {
		global $app;

		$user = $app['users']->where('username', '=', $request->getAttribute('author'))->fetch();

		$page = new \Models\Page([
			'title' => $user->getName(),
		]);

		$posts = $app['posrs']->where('author', '=', $user->id)->take(10)->get();

		$app['services']->posts->hydrate($posts);

		$content = new \ContentIterator($posts);

		$vars['user'] = $user;

		return $app['theme']->displayContent($page, $content, 'layout', ['profile'], $vars);
	}

}
