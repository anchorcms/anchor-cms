<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Feeds extends Frontend
{
    public function getRss(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $posts = $this->container['services.posts']->getPosts([
            'perpage' => 20,
        ]);

        foreach ($posts as $post) {
            $author = $post->getAuthor();
            $category = $post->getCategory();

            $this->container['services.rss']->item([
                'title' => $post->title,
                'content' => $post->html,
                'link' => $this->container['url']->to($post->url()),
                'author' => $author->getName(),
                'date' => \DateTime::createFromFormat('Y-m-d H:i:s', $post->published),
                'category' => [$this->container['url']->to($category->url()), $category->title],
            ]);
        }

        $body = $this->container['services.rss']->output();

        return $this->container['http.factory']->createResponse(200, ['content-type' => 'application/xml'], $body);
    }
}
