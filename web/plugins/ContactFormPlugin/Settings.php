<?php
namespace Anchorcms\Plugins\ContactFormPlugin;

use Anchorcms\View;
use Anchorcms\PluginSettingsInterface;

/**
 * Class Settings.
 * @package Anchorcms\Plugins\ContactFormPlugin
 */
class Settings implements PluginSettingsInterface
{

    protected $view;

    /**
     * Creates the settings page
     *
     * @access public
     *
     * @param $pluginPath
     */
    public function __construct(string $pluginPath)
    {
        $this->view = new View($pluginPath, 'phtml');
    }

    /**
     * renders the settings page
     *
     * @access public
     *
     * @return string
     */
    public function renderSettings(): string
    {
        return $this->view->render('views/settings', []);
    }
}
