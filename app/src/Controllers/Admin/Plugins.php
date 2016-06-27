<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Plugins extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $vars['title'] = 'Plugins';
        $vars['plugins'] = $this->container['services.plugins']->getPlugins();

        return $this->renderTemplate('layouts/default', 'plugins/index', $vars);
    }
}
