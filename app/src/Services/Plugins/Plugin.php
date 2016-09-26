<?php
namespace Anchorcms\Services\Plugins;


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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
     * @return void
     */
    public function setInactive()
    {
        $newPath = $this->path . '_disabled';
        file_put_contents($this->path . '/../mvOp.log', $newPath . PHP_EOL . $this->path);
        rename($this->path, $newPath);
        $this->path = $newPath;
        $this->active = false;
    }

    /**
     * whether the plugin is currently active
     *
     * @access public
     *
     * @return bool
     */
    public function isActive()
    {
        return (substr($this->path, -9) !== '_disabled');
    }

    /**
     * retrieves the plugin name
     *
     * @access public
     *
     * @return string
     */
    public function getName()
    {
        return $this->manifest->name;
    }

    public function getDescription()
    {
        return $this->manifest->description;
    }

    public function getVersion()
    {
        return $this->manifest->version;
    }

    public function getUrl()
    {
        return $this->manifest->url;
    }

    public function getAuthor()
    {
        return $this->manifest->author;
    }
}
