<?php
namespace Anchorcms\Events\Admin;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class BeforeRenderEvent.
 * @package Anchorcms\Events\Admin
 */
class BeforeRenderEvent extends Event
{
    /**
     * the template name.
     *
     * @access protected
     * @var string
     */
    protected $template;

    /**
     * the template vars.
     *
     * @access protected
     * @var array
     */
    protected $vars;

    /**
     * BeforeRenderEvent constructor.
     *
     * @param string $template the templates name
     * @param array  $vars     the template variables (default: empty array)
     */
    public function __construct(string $template, array $vars = [])
    {
        $this->template = $template;

        $this->vars = $vars;
    }

    /**
     * returns the template
     *
     * @access public
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * sets the template
     *
     * @access public
     * @param string $template the new template name
     * @return void
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * returns a variable
     *
     * @access public
     * @param $var
     * @return mixed
     */
    public function getVar($var)
    {
        return $this->vars[$var];
    }

    /**
     * returns all variables
     *
     * @access public
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * sets a single variable
     *
     * @access public
     *
     * @param string $key   the variable name
     * @param mixed  $value the variable value
     *
     * @return void
     */
    public function setVar($key, $value)
    {
        $this->vars[$key] = $value;
    }

    /**
     * replaces all variables
     *
     * @access public
     * @param array $vars the new variables
     * @return void
     */
    public function replaceVars(array $vars)
    {
        $this->vars = $vars;
    }

    /**
     * adds multiple variables
     *
     * @access public
     * @param array $vars the variables to add
     * @return void
     */
    public function addVars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }
}
