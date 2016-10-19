<?php
namespace Anchorcms\Services\Themes;

/**
 * Adds an interface to create theme options
 *
 * @package Anchorcms\Services\Themes
 */
class Options
{
    /**
     * the theme options
     *
     * @access private
     * @var array
     */
    private $options = [];

    /**
     * adds a new theme option
     *
     * @access protected
     * @param string $name
     *
     * @return mixed
     */
    protected function add(string $name)
    {
        // set the new option with the default type text
        $this->options[$name] = [
            'type' => 'text'
        ];

        /**
         * return a new anonymous class to enable chaining subsequent
         * modifier methods on the last added option. This prevents
         * an awful lot of checking, last-option-set variables and so
         * on.
         * Besides, it makes syntax look pretty sexy (:
         */
        return new class($this, $name)
        {
            /**
             * the options collection
             *
             * @access private
             * @var Options
             */
            private $options;

            /**
             * the current option's name
             *
             * @access private
             * @var string
             */
            private $optionName;

            /**
             *  sets the options collection and current option name
             *
             * @param Options $options
             * @param string  $optionName
             * @constructor
             */
            public function __construct(Options $options, string $optionName)
            {
                $this->options = $options;
                $this->optionName = $optionName;
            }

            /**
             * wrapper function for setting option attributes
             *
             * @access private
             * @param string $attribute
             * @param mixed $value
             *
             * @return void
             */
            private function setOptionAttribute(string $attribute, $value)
            {
                $this->options->get($this->optionName)[$attribute] = $value;
            }

            /**
             * set the option type, which can be any valid HTML5 input type,
             * select or textarea
             *
             * @access public
             * @param string $type
             *
             * @return void
             */
            public function setType(string $type)
            {
                $this->setOptionAttribute('type', $type);
            }

            /**
             * set the option's default value
             *
             * @access public
             * @param mixed $defaultValue
             *
             * @return void
             */
            public function setDefault($defaultValue)
            {
                $this->setOptionAttribute('default', $defaultValue);
            }

            /**
             * set an arbitrary HTML input attribute
             *
             * @access public
             * @param string $attribute
             * @param string $value
             *
             * @return void
             */
            public function setInputAttribute(string $attribute, string $value = '')
            {
                $this->options->get($this->optionName)['attributes'][$attribute] = $value;
            }

            /**
             * set options for a select option. Will fail if the current option is no
             * select option.
             *
             * @access public
             * @param array $choices
             * @throws \InvalidArgumentException
             * @return void
             */
            public function addChoices(array $choices)
            {
                if ($this->options->get('type') !== 'select') {
                    throw new \InvalidArgumentException('Only select boxes can have options');
                }

                $this->setOptionAttribute('choices', $choices);
            }
        };
    }

    /**
     * retrieves a theme option by name
     *
     * @access public
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->options[$name];
    }

    public function getAll()
    {
        return $this->options;
    }
}
