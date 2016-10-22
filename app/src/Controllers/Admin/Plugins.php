<?php

namespace Anchorcms\Controllers\Admin;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Anchorcms\Controllers\AbstractController;

class Plugins extends AbstractController
{
    public function getIndex(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $plugins = $this->container['plugins']->getPlugins();
        $active = $this->container['plugins']->getActivePlugins($this->container['mappers.meta']);

        $vars['sitename'] = $this->container['mappers.meta']->key('sitename');
        $vars['title'] = 'Plugins';

        $vars['plugins'] = $plugins;
        $vars['active'] = $active;

        $this->renderTemplate($response, 'layouts/default', 'plugins/index', $vars);

        return $response;
    }

    public function getActivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $plugin = $this->container['plugins']->getPluginByFolder($args['folder']);

        $jsonStr = $this->container['mappers.meta']->key('plugins', '[]');
        $active = json_decode($jsonStr, true);

        $active[] = $plugin->getFolder();

        $this->container['mappers.meta']->put('plugins', json_encode($active));

        return $this->redirect($response, '/admin/plugins');
    }

    public function getDeactivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $jsonStr = $this->container['mappers.meta']->key('plugins', '[]');
        $active = json_decode($jsonStr, true);

        if(false !== ($index = array_search($args['folder']))) {
            unset($active[$index]);
        }

        $this->container['mappers.meta']->put('plugins', json_encode($active));

        return $this->redirect($response, '/admin/plugins');
    }
}
