<?php

namespace Controllers;

class Feeds extends Frontend {

	public function getRss($request) {
		$posts = $this->container['mappers.posts']->where('status', '=', 'published')->sort('created', 'desc')->take(20)->get();
		$this->container['services.posts']->hydrate($posts);

		$uri = clone $request->getUri();

		foreach($posts as $post) {
			$category = $post->getCategory();
			$categoryUri = (string) $uri->withPath(sprintf('/categories/%s', $category->slug));

			$postUri = (string) $uri->withPath(sprintf('/%s/%s', $category->slug, $post->slug));

			$author = $post->getAuthor();

			$this->container['services.rss']->item([
				'title' => $post->title,
				'content' => $post->html,
				'link' => $postUri,
				'author' => [$author->getEmail(), $author->getName()],
				'date' => \DateTime::createFromFormat('Y-m-d H:i:s', $post->created),
				'category' => [$categoryUri, $category->title],
			]);
		}

		$body = new \Http\Stream;
		$body->write($this->container['services.rss']->output());

		return $this->container['middleware.response']->withBody($body)->withHeader('content-type', 'application/xml');
	}

}
