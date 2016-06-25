<?php

namespace Anchorcms\Controllers\Admin;

class Plugins extends Backend
{

    public function getIndex()
    {
        $vars['title'] = 'Plugins';
        $vars['plugins'] = $this->container['services.plugins']->getPlugins();

        return $this->renderTemplate('layouts/default', 'plugins/index', $vars);
    }
}
