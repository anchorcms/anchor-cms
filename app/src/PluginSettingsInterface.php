<?php


namespace Anchorcms;


interface PluginSettingsInterface
{
    /**
     * PluginSettingsInterface constructor.
     *
     * @access public
     *
     * @param string $pluginPath
     */
    public function __construct(string $pluginPath);

    /**
     * must return the settings page as an HTML string
     *
     * @access public
     *
     * @return string
     */
    public function renderSettings(): string;
}
