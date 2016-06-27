<?php

namespace Anchorcms\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Models\Page as PageModel;

abstract class Frontend extends AbstractController
{
    public function notFound(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $page = new PageModel([
            'title' => 'Not Found',
            'slug' => 'not-found',
            'html' => '<p>The resource you’re looking for doesn’t exist!</p>',
        ]);

        $vars['meta'] = $this->container['mappers.meta']->all();
        $vars['menu'] = $this->container['mappers.pages']->menu();
        $vars['categories'] = $this->container['mappers.categories']->all();
        $vars['page'] = $page;

        $this->container['theme']->render($response, ['404', 'page', 'index'], $vars);

        return $response->withStatus(404);
    }
}
