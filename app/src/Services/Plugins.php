<?php

namespace Anchorcms\Services;

use Anchorcms\PluginManifest;

class Plugins
{
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getPlugins()
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

    public function getPlugin($folder)
    {
        $manifest = $this->path . '/' . $folder . '/manifest.json';

        if (is_file($manifest)) {
            $jsonStr = file_get_contents($manifest);
            $attributes = json_decode($jsonStr, true);
            return new PluginManifest($folder, $attributes);
        }
    }

    public function load($file)
    {
        // todo
    }
}
