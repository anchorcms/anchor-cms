<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Themes extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Themes';
        $vars['themes'] = $this->container['services.themes']->getThemes();

        $this->renderTemplate($response, 'layouts/default', 'themes/index', $vars);

        return $response;
    }

    public function postActivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $theme = Filters::withDefault($request->getParsedBody(), 'theme', FILTER_SANITIZE_STRING);

        $this->container['mappers.meta']->where('key', '=', 'theme')->update(['value' => $theme]);

        $this->container['messages']->success(['Theme updated']);

        return $this->redirect($this->container['url']->to('/admin/themes'));
    }
}
