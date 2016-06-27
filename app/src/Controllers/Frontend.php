<?php

namespace Anchorcms\Controllers;

use Anchorcms\Models\Page as PageModel;

abstract class Frontend extends AbstractController
{
    public function notFound()
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

        $body = $this->container['theme']->render(['404', 'page', 'index'], $vars);

        return $this->container['http.factory']->createResponse(404, [], $body);
    }
}
