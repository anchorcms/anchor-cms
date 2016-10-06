<?php
namespace Anchorcms\Events\Admin;

use Symfony\Component\EventDispatcher\Event;

class BuildStylesEvent extends Event
{
    /**
     * an array of stylesheets to use
     *
     * @access protected
     * @var array
     */
    protected $styles = [];

    public function __construct()
    {
    }

    /**
     * build the styles list
     *
     * @access public
     * @return string
     */
    public function getStyles(): string
    {
        $styles = '';

        foreach ($this->styles as $style) {
            $styles .= '<link href="' . $style . '" rel="stylesheet">' . PHP_EOL;
        }

        return $styles;
    }

    /**
     * adds a new stylesheet
     *
     * @access public
     * @param string $path
     * @return void
     */
    public function addStyle(string $path): void
    {
        array_push($this->styles, $path);
    }

    /**
     * adds multiple new styles
     *
     * @access public
     * @param array $paths
     * @return void
     */
    public function addStyles(array $paths): void
    {
        foreach ($paths as $path) {
            $this->addStyle($path);
        }
    }
}
