<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Models\Page as PageModel;

class Posts extends Frontend
{
    /**
     * view a single post.
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // get post by slug
        $article = $this->container['mappers.posts']->slug($args['post']);

        // post not found
        if (false === $article) {
            return $this->notFound($request, $response, $args);
        }

        // hydrate post
        $this->container['services.posts']->hydrate([$article]);

        // redirect to the correct category slug
        if ($article->getCategory()->slug != $args['category']) {
            return $this->redirect($response, $article->url());
        }

        // create mock page for post
        $page = new PageModel([
            'title' => $article->title,
        ]);

        $vars['page'] = $page;
        $vars['post'] = $article;

        // set globals
        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['mappers.pages']->menu();
        $vars['categories'] = $this->container['mappers.categories']->all();

        $this->container['theme']->render($response, [
            sprintf('article-%s', $article->slug),
            sprintf('article-%s', $article->getCategory()->slug),
            'article',
        ], $vars);

        return $response;
    }
}
