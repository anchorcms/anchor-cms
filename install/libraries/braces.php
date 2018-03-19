<?php

/**
 * braces class
 * Provides a basic templating engine for configuration templates
 */
class braces
{
    /**
     * Path to the template
     *
     * @var string
     */
    protected $path;

    /**
     * Braces constructor
     *
     * @param string $path path to the template file
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Shorthand to create and render a template
     *
     * @param string $path path to the template file
     * @param array  $vars variables to replace in the template
     *
     * @return string
     */
    public static function compile($path, $vars = [])
    {
        $braces = new static($path);

        return $braces->render($vars);
    }

    /**
     * Renders a template by replacing all values in braces
     *
     * @param array $vars variables to replace in the template
     *
     * @return string rendered template
     */
    public function render($vars = [])
    {
        $content = file_get_contents($this->path);

        $keys   = array_map([$this, 'key'], array_keys($vars));
        $values = array_values($vars);

        return str_replace($keys, $values, $content);
    }

    /**
     * Creates a braced representation of a variable
     *
     * @param string $var variable to brace
     *
     * @return string braced variable
     */
    public function key($var)
    {
        return '{{' . $var . '}}';
    }
}
