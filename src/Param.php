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
     * Stores parameter validation
     *
     * @var string|ValidatorInterface
     */
    private $validation;

    /**
     * Param constructor
     *
     * @param string                    $param      Parameter name
     * @param string|ValidatorInterface $validation Validation
     */
    public function __construct($param, $validation = null)
    {
        $this->param = $param;
        $this->validation = is_string($validation) ? new Validator($validation) : $validation;
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

        $value = $params[$this->param];

        if ($this->validation === null) {
            return $value;
        }


        if ($this->validation instanceof ValidatorInterface && $this->validation->validate($value)) {
            return $value;
        }

        if (is_callable($this->validation) && call_user_func($this->validation, $value)) {
            return $value;
        }

        throw new \InvalidArgumentException("{$this->param} is invalid");
    }
}
