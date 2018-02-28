<?php

/**
 * items class
 * Basic Iterator implementation for AnchorCMS
 */
class items implements Iterator
{
    /**
     * Holds the current position
     *
     * @var int
     */
    private $position = 0;

    /**
     * Holds the items
     *
     * @var array|int
     */
    private $array = [];

    /**
     * items constructor
     *
     * @param array $items (optional) items to iterate on
     */
    public function __construct($items = [])
    {
        $this->position = 0;
        $this->array    = $items;
    }

    /**
     * Retrieves the current item
     *
     * @return mixed
     */
    public function current()
    {
        return $this->array[$this->position];
    }

    /**
     * Retrieves the current key
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Increments the position
     *
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Sets the position to the beginning
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Checks whether the current offset exists
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    /**
     * Retrieves the length
     *
     * @return int
     */
    public function length()
    {
        return count($this->array);
    }
}
