<?php

namespace Anchorcms\Plugins;

use Symfony\Component\EventDispatcher\EventDispatcher;

class Plugins
{
    /**
     * @var array of active plugins that have been loaded
     */
    protected $active = [];

    /**
     * Scan plugin directory for plugin manifest files
     */
    public function getPlugins(string $path): array
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
     * Fetch a plugin manifest object by directory
     *
     * @param string full path to plugin folder name
     */
    public function getPluginByDir(string $path): PluginManifest
    {
        $manifest = $path . '/manifest.json';

        if (! is_file($manifest)) {
            throw new \RuntimeException(sprintf('manifest file not found for %s', $path));
        }

        $jsonStr = file_get_contents($manifest);
        $attributes = json_decode($jsonStr, true);

        if ($error = json_last_error_msg()) {
            throw new \RuntimeException(sprintf('failed to decode manifest file: %s', $error));
        }

        return new PluginManifest(basename($path), $attributes);
    }

    /**
     * Init active plugins
     *
     * @param string path to plugins directory
     * @param array of active plugins by folder name from the databse
     * @param object symfony event dispatcher
     */
    public function init(string $path, array $active, EventDispatcher $dispatcher)
    {
        // filter inactive plugins by folder name
        $plugins = array_filter($this->getPlugins($path), function ($pluginManifest) use ($active) {
            return in_array($pluginManifest->getFolder(), $active);
        });

        foreach ($plugins as $pluginManifest) {
            // @todo: add namespace to loader
            // $composer->addPsr4($pluginManifest->getNamespace(), $pluginManifest->getFolder(), true);
            //
            $active[$pluginManifest->getFolder()] = $pluginManifest->getInstance();

            // todo: set the database connection on the plugin
            // if($pluginInstance instanceof PluginDatabaseInterface) {
            //     $pluginInstance->getDatabaseConnection($database, $prefix);
            // }

            $active[$pluginManifest->getFolder()]->getSubscribedEvents($dispatcher);
        }
    }

    /**
     * Get a active plugin by folder name
     *
     * @param string folder name
     * @return object AbstractPlugin
     */
    public function getActivePlugin(string $folder): AbstractPlugin
    {
        return $active[$folder];
    }
}
