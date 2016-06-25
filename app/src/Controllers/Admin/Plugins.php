<?php

namespace Anchorcms\Controllers\Admin;

use Anchorcms\Controllers\AbstractController;

class Plugins extends AbstractController
{
    public function getIndex()
    {
        $vars['title'] = 'Plugins';
        $vars['plugins'] = $this->container['services.plugins']->getPlugins();

        return $this->renderTemplate('layouts/default', 'plugins/index', $vars);
    }
}
