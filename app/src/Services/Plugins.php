<?php

namespace Anchorcms\Services;

class Plugins
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getPlugins()
    {
        $plugins = [];

        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            if ($file->isDir()) {
                if (!file_exists($file . DIRECTORY_SEPARATOR . 'manifest.json')) {
                    continue;
                }

                $plugin = new Plugins\Plugin($file->getPathname());

                $plugins[$plugin->getName()] = $plugin;
            }
        }

        // sort the plugins by name
        ksort($plugins);

        return array_values($plugins);
    }

    public function countPlugins()
    {
        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        return iterator_count($fi);
    }

    public function getPlugin(string $name)
    {
        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            if ($file->isDir()) {
                if (!file_exists($file . DIRECTORY_SEPARATOR . 'manifest.json')) {
                    continue;
                }

                $plugin = new Plugins\Plugin($file->getPathname());

                if (strtolower($plugin->getName()) === $name) {
                    return $plugin;
                }
            }
        }
    }

    public function init()
    {
        $fi = new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS);

        foreach ($fi as $file) {
            $this->load($file);
        }
    }

    public function load($file)
    {
        // todo
    }
}
