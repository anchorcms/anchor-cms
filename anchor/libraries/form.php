<?php

use System\config;
use System\uri;

/**
 * form class
 * Abstracts HTML forms
 */
class form
{
    /**
     * Encoding for multipart forms
     */
    const ENCODING_MULTIPART = 'multipart/form-data';

    /**
     * HTTP GET request method
     */
    const METHOD_GET = 'GET';

    /**
     * HTTP POST request method
     */
    const METHOD_POST = 'POST';

    /**
     * Default textarea columns
     */
    const TEXTAREA_COLUMNS = 50;

    /**
     * Default textarea rows
     */
    const TEXTAREA_ROWS = 10;

    /**
     * Opens an HTML multipart form. This sets the form encoding
     *
     * @param string $action     form action URI
     * @param array  $attributes (optional) form tag attributes
     *
     * @return string opening form tag
     */
    public static function open_multipart($action, $attributes = [])
    {
        $attributes['enctype'] = self::ENCODING_MULTIPART;

        return static::open($action, $attributes);
    }

    /**
     * Opens an HTML form element
     *
     * @param string $action     form action URI
     * @param string $method     (optional) HTTP request method
     * @param array  $attributes (optional) form tag attributes
     *
     * @return string opening form tag
     */
    public static function open($action, $method = self::METHOD_POST, $attributes = [])
    {
        $attributes['method'] = static::method(strtoupper($method));

        $attributes['action'] = static::action($action);

        if (! array_key_exists('accept-charset', $attributes)) {
            $attributes['accept-charset'] = Config::app('encoding');
        }

        return '<form' . Html::attributes($attributes) . '>';
    }

    /**
     * Retrieves the form method. Possible values are GET or POST
     *
     * @param string $method supplied method
     *
     * @return string form method
     */
    protected static function method($method)
    {
        return ($method !== self::METHOD_GET
            ? self::METHOD_POST
            : self::METHOD_GET
        );
    }

    /**
     * Resolves the form action to a URI
     *
     * @param string $action action URI
     *
     * @return string
     */
    protected static function action($action)
    {
        return Uri::to($action);
    }

    /**
     * Closes the form
     *
     * @return string closing form tag
     */
    public static function close()
    {
        return '</form>';
    }

    /**
     * Creates a new text input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function text($name, $value = '', $attributes = [])
    {
        return static::input('text', $name, $value, $attributes);
    }

    /**
     * Creates a new input element
     *
     * @param string $type       input type
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function input($type, $name, $value = '', $attributes = [])
    {
        $attributes['type'] = $type;

        $attributes['name'] = $name;

        if ($value) {
            $attributes['value'] = $value;
        }

        return Html::element('input', '', $attributes);
    }

    /**
     * Creates a new password input element
     *
     * @param string $name       input name
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function password($name, $attributes = [])
    {
        return static::input('password', $name, '', $attributes);
    }

    /**
     * Creates a new hidden input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function hidden($name, $value = '', $attributes = [])
    {
        return static::input('hidden', $name, $value, $attributes);
    }

    /**
     * Creates a new search input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function search($name, $value = '', $attributes = [])
    {
        return static::input('search', $name, $value, $attributes);
    }

    /**
     * Creates a new email input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function email($name, $value = '', $attributes = [])
    {
        return static::input('email', $name, $value, $attributes);
    }

    /**
     * Creates a new telephone input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function telephone($name, $value = '', $attributes = [])
    {
        return static::input('tel', $name, $value, $attributes);
    }

    /**
     * Creates a new URL input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function url($name, $value = '', $attributes = [])
    {
        return static::input('url', $name, $value, $attributes);
    }

    /**
     * Creates a new number input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function number($name, $value = '', $attributes = [])
    {
        return static::input('number', $name, $value, $attributes);
    }

    /**
     * Creates a new date input element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function date($name, $value = '', $attributes = [])
    {
        return static::input('date', $name, $value, $attributes);
    }

    /**
     * Creates a new file input element
     *
     * @param string $name       input name
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function file($name, $attributes = [])
    {
        return static::input('file', $name, '', $attributes);
    }

    /**
     * Creates a new text area element
     *
     * @param string $name       input name
     * @param string $value      (optional) input value
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function textarea($name, $value = '', $attributes = [])
    {
        $attributes['name'] = $name;

        if (! isset($attributes['rows'])) {
            $attributes['rows'] = self::TEXTAREA_ROWS;
        }

        if (! isset($attributes['cols'])) {
            $attributes['cols'] = self::TEXTAREA_COLUMNS;
        }

        return Html::element('textarea', $value, $attributes);
    }

    /**
     * Creates a new select element
     *
     * @param string               $name       input name
     * @param array                $options    (optional) options for the select
     * @param string|string[]|null $selected   (optional) selected options
     * @param array                $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function select($name, $options = [], $selected = null, $attributes = [])
    {
        $attributes['name'] = $name;

        $html = [];

        foreach ($options as $value => $display) {
            if (is_array($display)) {
                $html[] = static::optgroup($display, $value, $selected);
            } else {
                $html[] = static::option($value, $display, $selected);
            }
        }

        return Html::element('select', implode('', $html), $attributes);
    }

    /**
     * Creates a new option group element
     *
     * @param array    $options  options in the group
     * @param string   $label    group label
     * @param string[] $selected whether the options are selected
     *
     * @return string
     */
    protected static function optgroup($options, $label, $selected)
    {
        $html = [];

        foreach ($options as $value => $display) {
            $html[] = static::option($value, $display, $selected);
        }

        return Html::element(
            'optgroup',
            implode('', $html),
            ['label' => Html::entities($label)]
        );
    }

    /**
     * Creates a new option element
     *
     * @param string               $value    option value
     * @param string               $display  option label
     * @param string|string[]|null $selected whether the option is selected. either a string containing
     *                                       the value, an array of strings containing the value or null
     *
     * @return string
     */
    protected static function option($value, $display, $selected)
    {
        $attributes = ['value' => Html::entities($value)];

        if (! is_null($selected)) {
            if ((is_array($selected) and in_array($value, $selected)) or ($value == $selected)) {
                $attributes['selected'] = 'selected';
            }
        }

        return Html::element('option', Html::entities($display), $attributes);
    }

    /**
     * Creates a new checkbox element
     *
     * @param string $name       input name
     * @param int    $value      (optional) input value
     * @param bool   $checked    (optional) whether the checkbox is checked
     * @param array  $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function checkbox($name, $value = 1, $checked = false, $attributes = [])
    {
        return static::checkable('checkbox', $name, $value, $checked, $attributes);
    }

    /**
     * Creates a new checkable element. Used by checkboxes and radios
     *
     * @param string $type       input type
     * @param string $name       input name
     * @param string $value      input value
     * @param bool   $checked    whether the element is checked
     * @param array  $attributes input tag attributes
     *
     * @return string
     */
    protected static function checkable($type, $name, $value, $checked, $attributes)
    {
        if ($checked) {
            $attributes['checked'] = 'checked';
        }

        return static::input($type, $name, $value, $attributes);
    }

    /**
     * Creates a new radio element
     *
     * @param string      $name       input name
     * @param string|null $value      (optional) input value
     * @param bool        $checked    (optional) whether the radio is checked
     * @param array       $attributes (optional) input tag attributes
     *
     * @return string
     */
    public static function radio($name, $value = null, $checked = false, $attributes = [])
    {
        if (is_null($value)) {
            $value = $name;
        }

        return static::checkable('radio', $name, $value, $checked, $attributes);
    }

    /**
     * Creates a new submit button element
     *
     * @param string|null $value      (optional) button label
     * @param array       $attributes (optional) button tag attributes
     *
     * @return string
     */
    public static function submit($value = null, $attributes = [])
    {
        return static::input('submit', null, $value, $attributes);
    }

    /**
     * Creates a new reset button element
     *
     * @param string|null $value      (optional) button label
     * @param array       $attributes (optional) button tag attributes
     *
     * @return string
     */
    public static function reset($value = null, $attributes = [])
    {
        return static::input('reset', null, $value, $attributes);
    }

    /**
     * Creates a new image input element
     *
     * @param string      $url        image source URL
     * @param string|null $name       (optional) image name
     * @param array       $attributes (optional) image tag attributes
     *
     * @return string
     */
    public static function image($url, $name = null, $attributes = [])
    {
        return static::input('image', $name, null, $attributes);
    }

    /**
     * Creates a new button element
     *
     * @param string|null $value      (optional) button label
     * @param array       $attributes (optional) button tag attributes
     *
     * @return string
     */
    public static function button($value = null, $attributes = [])
    {
        if (!isset($attributes['type'])) {
            $attributes['type'] = 'button';
        }

        return Html::element('button', Html::entities($value), $attributes);
    }
}
