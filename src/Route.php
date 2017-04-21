<?php
/**
 * Route class
 */

namespace PhilWaters\API;

/**
 * Handles an API end point
 */
class Route
{
    /**
     * Stores params
     *
     * @var array
     */
    private $params = array();

    /**
     * Stores route URL
     *
     * @var string
     */
    private $url;

    /**
     * Stores URL regular expression
     *
     * @var string
     */
    private $regex;

    /**
     * Stores HTTP request method
     *
     * @var string
     */
    private $method = null;

    /**
     * Stores route handler
     *
     * @var callable
     */
    private $handler = null;

    /**
     * Stores arguments to be passed to the handler
     *
     * @var array
     */
    private $args = array();

    /**
     * Route constructor
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->regex = "`^$url$`i";
    }

    /**
     * Checks if the URL is a match to this route
     *
     * @param string $url    URL to check
     * @param string $method HTTP request method
     *
     * @return boolean
     */
    public function isMatch($url, $method)
    {
        if (($this->method !== null && strcasecmp($this->method, $method) != 0) ||
            !preg_match($this->regex, $url, $match)) {
            return false;
        }

        $i = 1;

        while (isset($match[$i])) {
            $this->args[] = $match[$i++];
        }

        return true;
    }

    /**
     * Sets HTTP request method
     *
     * @param string $method HTTP request method
     *
     * @return void
     */
    public function method($method)
    {
        $this->method = $method;
    }

    /**
     * Sets route handler
     *
     * @param callable $callable Handler
     *
     * @return void
     */
    public function handler($callable)
    {
        $this->handler = $callable;
    }

    /**
     * Adds parameter
     *
     * @param string                    $param      Parameter name
     * @param string|ValidatorInterface $validation Validation
     *
     * @return void
     */
    public function param($param, $validation)
    {
        $this->params[] = new Param($param, $validation);
    }

    /**
     * Adds argument
     *
     * @param mixed $arg Argument value
     *
     * @return void
     */
    public function arg($arg)
    {
        $this->params[] = new Arg($arg);
    }

    /**
     * Executes the handler
     *
     * @param array $params Parameters
     *
     * @return mixed
     */
    public function exec($params)
    {
        $args = $this->args;
        $handler = new CallableMethod($this->handler);

        foreach ($this->params as $param) {
            $args[] = $param->get($params);
        }

        return $handler->call($args);
    }
}
