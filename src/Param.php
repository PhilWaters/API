<?php
/**
 * Param class
 */

namespace PhilWaters\API;

/**
 * Handles router parameters
 */
class Param
{
    /**
     * Stores parameter name
     *
     * @var string
     */
    private $param;

    /**
     * Param constructor
     *
     * @param string $param Parameter name
     */
    public function __construct($param)
    {
        $this->param = $param;
    }

    /**
     * Gets the value of parameter
     *
     * @param array $params Parameters
     *
     * @return NULL|mixed
     */
    public function get($params)
    {
        if (!isset($params[$this->param])) {
            return null;
        }

        return $params[$this->param];
    }
}
