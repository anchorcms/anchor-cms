<?php

namespace Anchorcms;

class PluginManifest
{
    protected $attributes;

    protected $folder;

    public function __construct(string $folder, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->folder = $folder;
    }

    public function getName()
    {
        return $this->attributes['name'] ?? 'No Name';
    }

    public function getSummary()
    {
        return $this->attributes['summary'] ?? 'No Summary';
    }

    public function getVersion()
    {
        return $this->attributes['version'] ?? 'No Version';
    }

    public function getClassName() {
        return $this->attributes['classname'];
    }

    public function getInstance() {
        $className = $this->getClassName();
        return new $className;
    }

    public function getFolder() {
        return $this->folder;
    }
}
