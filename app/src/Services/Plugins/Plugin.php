<?php
namespace Anchorcms\Services\Plugins;

/**
 * Plugin service
 *
 * @package Anchorcms\Services\Plugins
 */
class Plugin
{
    /**
     * Folder where the plugin lives.
     *
     * @access protected
     * @var string
     */
    protected $path;

    /**
     * The basename of the plugin.
     *
     * @access protected
     * @var string
     */
    protected $name;

    /**
     * The json decoded contents of the manifest file.
     *
     * @access protected
     * @var object
     */
    protected $manifest;

    /**
     * whether the plugin is enabled.
     *
     * @access protected
     * @var bool
     */
    protected $active;

    /**
     * The plugin constructor.
     *
     * @param string Plugin path
     * @constructor
     */
    public function __construct(string $path)
    {
        $this->path = realpath($path);
        $this->loadManifest();
    }

    /**
     * loads the plugin manifest
     *
     * @access public
     * @return void
     */
    public function loadManifest()
    {
        if ($this->hasManifest()) {
            $json = file_get_contents($this->getManifestFilepath());
            $this->manifest = json_decode($json);
        }
    }

    /**
     * retrieves the plugin manifest path
     *
     * @access public
     * @return string
     */
    public function getManifestFilepath()
    {
        return $this->path . '/manifest.json';
    }

    /**
     * whether the plugin has a manifest
     *
     * @access public
     * @return bool
     */
    public function hasManifest()
    {
        return is_file($this->getManifestFilepath());
    }

    /**
     * returns the plugin manifest
     *
     * @access public
     * @return object
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     * enables the plugin
     *
     * @access public
     * @return void
     */
    public function setActive()
    {
        $newPath = str_replace('_disabled', '', $this->path);
        rename($this->path, $newPath);
        $this->path = $newPath;
        $this->active = true;
    }

    /**
     * disables the plugin
     *
     * @access public
     * @return void
     */
    public function setInactive()
    {
        $newPath = $this->path . '_disabled';
        rename($this->path, $newPath);
        $this->path = $newPath;
        $this->active = false;
    }

    /**
     * whether the plugin is currently active
     *
     * @access public
     * @return bool
     */
    public function isActive()
    {
        return (substr($this->path, -9) !== '_disabled');
    }

    /**
     * instantiate the plugin
     *
     * @access public
     * @return \Anchorcms\Plugin
     */
    public function load()
    {
        $classname = 'Anchorcms\\Plugins\\' . basename($this->path) . '\\' . $this->manifest->classname;
        return (new $classname());
    }

    /**
     * whether the plugin defined a settings class
     *
     * @access public
     * @return bool
     */
    public function hasSettings()
    {
        return !!(isset($this->manifest->settingsClassname));
    }

    /**
     * build the plugin settings page
     *
     * @access public
     * @return string
     */
    public function buildSettings()
    {
        if (!$this->hasSettings()) {
            return 'this plugin has no settings available.';
        }

        // try to instantiate the plugin settings class and retrieve the rendered settings
        try {
            $classname = 'Anchorcms\\Plugins\\' . basename($this->path) . '\\' . $this->manifest->settingsClassname;
            return (new $classname($this->path))->renderSettings();
        } catch (\Throwable $throwable) {
            return $throwable->getMessage();
        }
    }

    /**
     * retrieves the plugin name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->manifest->name ?? 'No name';
    }

    /**
     * retrieves the plugin description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return $this->manifest->description ?? 'No description';
    }

    /**
     * retrieves the plugin version
     *
     * @access public
     * @return string
     */
    public function getVersion()
    {
        return $this->manifest->version ?? 'No version';
    }

    /**
     * retrieves the plugin URL
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->manifest->url ?? '#no-url';
    }

    /**
     * retrieves the plugin author
     *
     * @access public
     * @return string
     */
    public function getAuthor()
    {
        return $this->manifest->author ?? 'No author';
    }
}
