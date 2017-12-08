<?php

namespace Anchorcms\Plugins;

use Anchorcms\Mappers\MapperInterface;
use Composer\Autoload\ClassLoader;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Plugins
{
    /**
     * @var array of plugins that have been loaded
     */
    protected $loaded = [];

    /**
     * @var string path to plugin directory
     */
    protected $path;

    /**
     * @var EventDispatcher symfony event dispatcher
     */
    protected $events;

    /**
     * Construct the anchor plugins system, pass in the
     * path to the plugins and inject our EventDispatcher.
     *
     * @param string          $path
     * @param EventDispatcher $events
     */
    public function __construct(string $path, EventDispatcher $events)
    {
        $this->path = $path;
        $this->events = $events;
    }

    /**
     * Scan plugin directory for plugin manifest files
     *
     * @return  array
     */
    public function getPlugins(): array
    {
        $plugins = [];

        if (! is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }

        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            $manifest = $file->getPathname() . '/manifest.json';

            if (is_file($manifest)) {
                $jsonStr = file_get_contents($manifest);
                $attributes = json_decode($jsonStr, true);
                $plugins[] = new PluginManifest($file->getBasename(), $attributes);
            }
        }

        return $plugins;
    }

    /**
     * Get array of active plugins set in the meta table
     *
     * @param MapperInterface mapper of meta table
     * @return array
     */
    public function getActivePlugins(MapperInterface $meta): array
    {
        $jsonStr = $meta->key('plugins', '[]');
        $active = json_decode($jsonStr, true);

        // filter inactive plugins by folder name
        return array_filter($this->getPlugins(), function (PluginManifest $pluginManifest) use ($active) {
            return in_array($pluginManifest->getFolder(), $active);
        });
    }

    /**
     * Append plugin folder name to the list of active plugins
     *
     * @param string folder name
     * @param MapperInterface meta table mapper
     */
    public function activatePlugin(string $folder, MapperInterface $meta)
    {
        $jsonStr = $meta->key('plugins', '[]');
        $active = json_decode($jsonStr, true);
        $active[] = $folder;
        $meta->put('plugins', json_encode($active));
    }

    /**
     * Unset plugin folder name from the list of active plugins
     *
     * @param string folder name
     * @param MapperInterface meta table mapper
     */
    public function deactivatePlugin(string $folder, MapperInterface $meta)
    {
        $jsonStr = $meta->key('plugins', '[]');
        $active = json_decode($jsonStr, true);

        if (false !== ($index = array_search($folder, $active))) {
            unset($active[$index]);
        }

        $meta->put('plugins', json_encode($active));
    }

    /**
     * Fetch a plugin manifest object by directory
     *
     * @param  string full path to plugin folder name
     * @return PluginManifest
     */
    public function getPluginByFolder(string $folder): PluginManifest
    {
        $manifest = $this->path . '/' . $folder . '/manifest.json';

        if (!is_file($manifest)) {
            throw new \RuntimeException(sprintf('manifest file not found for %s', $folder));
        }

        $jsonStr = file_get_contents($manifest);
        $attributes = json_decode($jsonStr, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(sprintf('failed to decode manifest file: %s', json_last_error_msg()));
        }

        return new PluginManifest($folder, $attributes);
    }

    /**
     * Init active plugins
     *
     * @param array of active plugins
     * @param ClassLoader class loader.
     */
    public function init(array $plugins, ClassLoader $loader)
    {
        foreach ($plugins as $pluginManifest) {
            // add namespace to loader
            $loader->addPsr4($pluginManifest->getNamespace(), sprintf('%s/%s/src', $this->path, $pluginManifest->getFolder()), true);

            // get instance of plugin
            $this->loaded[$pluginManifest->getFolder()] = $pluginManifest->getInstance();

            // register events
            $this->loaded[$pluginManifest->getFolder()]->getSubscribedEvents($this->events);
        }
    }

    /**
     * Get a active plugin by folder name
     *
     * @param string folder name
     * @return AbstractPlugin
     */
    public function getActivePlugin(string $folder): AbstractPlugin
    {
        return $this->loaded[$folder];
    }
}
