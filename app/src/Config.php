<?php

namespace Anchorcms;

class Config
{
    protected $path;

    protected $config;

    public function __construct(string $path, array $config = [])
    {
        $this->path = $path;
        $this->config = $config;
    }

    public function load(string $name): array
    {
        // have we already loaded this file?
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }

        // does the file exist?
        $path = sprintf('%s/%s.json', $this->path, $name);

        if (! is_file($path) || ! is_readable($path)) {
            return [];
        }

        // read json file contents
        $jsonString = file_get_contents($path);

        // set array params
        $this->config[$name] = json_decode($jsonString, true);

        // did we miss something?
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(json_last_error_msg());
        }

        return $this->config[$name];
    }

    public function get(string $path, $default = false)
    {
        $keys = explode('.', $path);

        // shift the first key which is the file name to load
        $filename = array_shift($keys);

        $config = $this->load($filename);

        foreach ($keys as $key) {
            if (! array_key_exists($key, $config)) {
                return $default;
            }

            $config = &$config[$key];
        }

        return $config;
    }
}
