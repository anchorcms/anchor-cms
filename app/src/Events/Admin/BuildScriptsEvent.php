<?php
namespace Anchorcms\Events\Admin;

use Symfony\Component\EventDispatcher\Event;

class BuildScriptsEvent extends Event
{
    /**
     * an array of scripts to use.
     *
     * @access protected
     * @var array
     */
    protected $scripts = [];

    public function __construct()
    {
    }

    /**
     * build the scripts list
     *
     * @access public
     * @return string
     */
    public function getScripts(): string
    {
        $scripts = '';

        foreach ($this->scripts as $script) {
            $scripts .= '<script src="' . $script . '">' . '</script>' . PHP_EOL;
        }

        return $scripts;
    }

    /**
     * adds a new script
     *
     * @access public
     * @param string $path
     * @return void
     */
    public function addScript(string $path)
    {
        array_push($this->scripts, $path);
    }

    /**
     * adds multiple new scripts
     *
     * @access public
     * @param array $paths
     * @return void
     */
    public function addScripts(array $paths)
    {
        foreach ($paths as $path) {
            $this->addScript($path);
        }
    }
}
