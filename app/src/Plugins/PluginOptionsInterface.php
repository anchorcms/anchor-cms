<?php


namespace Anchorcms\Plugins;


interface PluginOptionsInterface
{
    public function init();

    public function populate();
}
