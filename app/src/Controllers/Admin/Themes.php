<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Themes extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $vars['title'] = 'Themes';
        $vars['themes'] = $this->container['services.themes']->getThemes();

        return $this->renderTemplate('layouts/default', 'themes/index', $vars);
    }

    public function postActivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $theme = filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_STRING);

        $this->container['mappers.meta']->where('key', '=', 'theme')->update(['value' => $theme]);

        $this->container['messages']->success(['Theme updated']);

        return $this->redirect($this->container['url']->to('/admin/themes'));
    }
}
