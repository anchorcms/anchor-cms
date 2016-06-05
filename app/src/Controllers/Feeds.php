<?php

namespace Controllers;

class Feeds extends Frontend {

	public function getRss($request, $response) {
		$query = $this->container['mappers.posts']->query();

		$query->where('status = '.$query->createPositionalParameter('published'))
			->orderBy('created', 'DESC')
			->setMaxResults(20);

		$posts = $this->container['mappers.posts']->fetchAll($query);
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

		$xml = $this->container['services.rss']->output();

		$body = $response->getBody();
		$body->write($xml);

		return $response->withStatus(200)->withBody($body)->withHeader('content-type', 'application/xml');
	}

}
