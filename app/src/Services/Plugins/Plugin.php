<?php
namespace Anchorcms\Services\Plugins;
use Forms\Form;

/**
 * Plugin service
 *
 * @package Anchorcms\Services\Plugins
 */
class Plugin
{
    /**
     * a set of protected core plugins
     *
     * @static
     * @access protected
     * @var array
     */
    protected static $corePlugins = [
        'Debugger'
    ];

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
    public function getManifestFilepath(): string
    {
        return $this->path . '/manifest.json';
    }

    /**
     * whether the plugin has a manifest
     *
     * @access public
     * @return bool
     */
    public function hasManifest(): bool
    {
        return is_file($this->getManifestFilepath());
    }

    /**
     * returns the plugin manifest
     *
     * @access public
     * @return object
     */
    public function getManifest(): object
    {
        return $this->manifest;
    }

    /**
     * enables the plugin
     *
     * @access public
     * @return void
     */
    public function enable()
    {
        if ($this->isActive()) {
            return;
        }

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
    public function disable()
    {
        if (!$this->isActive()) {
            return;
        }

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
    public function isActive(): bool
    {
        return (substr($this->path, -9) !== '_disabled');
    }

    /**
     * instantiate the plugin
     *
     * @access public
     * @return \Anchorcms\Plugin
     * @throws \Error
     */
    public function load()
    {
        $classname = 'Anchorcms\\Plugins\\' . basename($this->path) . '\\' . $this->manifest->classname;

        try {
            $instance = new $classname();
        } catch (\Error $error) {
            $this->disable();
            throw new \Error('Plugin could not be instantiated. It has been disabled.');
        }

        return $instance;
    }

    /**
     * whether the plugin defined an options class
     *
     * @access public
     * @return bool
     */
    public function hasOptions(): bool
    {
        return !!(isset($this->manifest->optionsClassname));
    }

    /**
     * build the plugin options form
     *
     * @access public
     * @param array $attributes
     * @return Form|string
     * @@throws \Throwable
     */
    public function getOptionsForm(array $attributes = []): Form
    {
        if (!$this->hasOptions()) {
            return 'this plugin has no options available.';
        }

        // try to instantiate the plugin settings class and retrieve the rendered settings
        try {
            $classname = 'Anchorcms\\Plugins\\' . basename($this->path) . '\\' . $this->manifest->optionsClassname;
            $pluginOptions = new $classname($attributes);
            $pluginOptions->init();
            return $pluginOptions;
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
    public function getName(): string
    {
        return $this->manifest->name ?? 'No name';
    }

    /**
     * retrieves the plugin description
     *
     * @access public
     * @return string
     */
    public function getDescription(): string
    {
        return $this->manifest->description ?? 'No description';
    }

    /**
     * retrieves the plugin version
     *
     * @access public
     * @return string
     */
    public function getVersion(): string
    {
        return $this->manifest->version ?? 'No version';
    }

    /**
     * retrieves the plugin URL
     *
     * @access public
     * @return string
     */
    public function getUrl(): string
    {
        return $this->manifest->url ?? '#no-url';
    }

    /**
     * retrieves the plugin author
     *
     * @access public
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->manifest->author ?? 'No author';
    }

    /**
     * checks if the plugin is a core plugin
     *
     * @access public
     * @return bool
     */
    public function isCorePlugin(): bool
    {
        return (in_array($this->getName(), static::$corePlugins));
    }
}
