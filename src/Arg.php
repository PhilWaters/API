<?php
/**
 * Arg class
 */

namespace PhilWaters\API;

/**
 * Handles route arguments
 */
class Arg
{
    /**
     * Stores argument value
     *
     * @var mixed
     */
    private $arg;
    /**
     * Arg constructor
     *
     * @param mixed $arg Argument value
     */
    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    /**
     * Gets the argument value
     *
     * @return mixed
     */
    public function get()
    {
        return $this->arg;
    }
}
