<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Plugins extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Plugins';
        $vars['plugins'] = $this->container['services.plugins']->getPlugins();

        $this->renderTemplate($response, 'layouts/default', 'plugins/index', $vars);

        return $response;
    }

    public function getActivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $plugin = $this->container['services.plugins']->getPlugin($args['folder']);

        return $response;
    }
}
