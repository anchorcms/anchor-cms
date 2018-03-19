<?php

/**
 * validator class
 * Validates input fields
 * @method \validator is_max(int $maximum, string $message)
 * @method \validator is_email(string $message)
 * @method \validator is_valid_key(string $message)
 * @method \validator is_duplicate(string $message)
 * @method \validator is_url(string $message)
 * @method \validator is_regex(string $regex, string $message)
 * @method \validator not_regex(string $regex, string $message)
 */
class validator
{
    /**
     * Holds the data to validate
     *
     * @var array
     */
    private $payload = [];

    /**
     * key
     *
     * @var string
     */
    private $key = [];

    /**
     * value
     *
     * @var array
     */
    private $value = [];

    /**
     * Holds all validation errors
     *
     * @var array
     */
    private $errors = [];

    /**
     * Holds all validator methods as $name => $callback
     *
     * @var array
     */
    private $methods = [];

    /**
     * validator constructor
     *
     * @param array $payload data to validate
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->defaults();
    }

    /**
     * Retrieves the default validators
     *
     * @return void
     */
    private function defaults()
    {
        $this->methods['null'] = function ($str) {
            return is_null($str);
        };

        $this->methods['min'] = function ($str, $length) {
            return strlen($str) <= $length;
        };

        $this->methods['max'] = function ($str, $length) {
            return strlen($str) >= $length;
        };

        $this->methods['float'] = function ($str) {
            return is_float($str);
        };

        $this->methods['int'] = function ($str) {
            return is_int($str);
        };

        $this->methods['url'] = function ($str) {
            return filter_var($str, FILTER_VALIDATE_URL) !== false;
        };

        $this->methods['email'] = function ($str) {
            return filter_var($str, FILTER_VALIDATE_EMAIL) !== false;
        };

        $this->methods['ip'] = function ($str) {
            return filter_var($str, FILTER_VALIDATE_IP) !== false;
        };

        $this->methods['alnum'] = function ($str) {
            return ctype_alnum($str);
        };

        $this->methods['contains'] = function ($str, $needle) {
            return strpos($str, $needle) !== false;
        };

        $this->methods['regex'] = function ($str, $pattern) {
            return preg_match($pattern, $str);
        };
    }

    /**
     * Validates a key
     *
     * @param string $key name of the payload key to validate
     *
     * @return \validator self for chaining
     */
    public function check($key)
    {
        $this->key = (array_key_exists($key, $this->payload)
            ? $key
            : null
        );

        $this->value = (isset($this->payload[$this->key])
            ? $this->payload[$this->key]
            : null
        );

        return $this;
    }

    /**
     * Adds a new validation
     *
     * @param string   $method   validation method
     * @param \Closure $callback validation callback
     *
     * @return void
     */
    public function add($method, $callback)
    {
        $this->methods[$method] = $callback;
    }

    /**
     * Magic validator execution. All validators can be prepended with "is_" or "not_".
     * This string will be removed to retrieve the actual validator callback, in case
     * of "not_" the validator return value will be negated.
     *
     * @param string $method validator method to call
     * @param array  $params parameters to the validator callback
     *
     * @return \validator
     * @throws \ErrorException
     */
    public function __call($method, $params)
    {
        if (is_null($this->key)) {
            return $this;
        }

        if (strpos($method, 'is_') === 0) {
            $method  = substr($method, 3);
            $reverse = false;
        } elseif (strpos($method, 'not_') === 0) {
            $method  = substr($method, 4);
            $reverse = true;
        }

        if (isset($this->methods[$method]) === false) {
            throw new ErrorException("Validator method $method not found");
        }

        $validator = $this->methods[$method];

        $message = array_pop($params);

        $result = (bool)call_user_func_array($validator, array_merge([$this->value], $params));

        /** @noinspection PhpUndefinedVariableInspection */
        $result = (bool)($result ^ $reverse);

        if ($result === false) {
            $this->errors[$this->key][] = $message;
        }

        return $this;
    }

    /**
     * Retrieves all validation errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }
}
