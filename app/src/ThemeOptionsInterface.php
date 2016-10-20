<?php
namespace Anchorcms;

/**
 * Interface PluginOptionsInterface
 *
 * @package Anchorcms
 */
interface ThemeOptionsInterface
{
    public function init();

    public function populate();
}
