<?php
/**
 * Router class
 */

namespace PhilWaters\API;

require_once "Arg.php";
require_once "Param.php";
require_once "Route.php";
require_once "ValidatorInterface.php";
require_once "Validator.php";

/**
 * Handles API end points
 */
class Router
{
    /**
     * Stores current route
     *
     * @var Route
     */
    private $route;

    /**
     * Stores routes
     *
     * @var array
     */
    private $routes = array();

    /**
     * Creates a new route
     *
     * @param string $regex URL regular expression
     *
     * @return Router
     */
    public function url($regex)
    {
        $this->route = new Route($regex);
        $this->routes[] = $this->route;

        return $this;
    }

    /**
     * Sets the HTTP request method (GET, POST etc)
     *
     * @param string $method HTTP request method
     *
     * @return Router
     */
    public function method($method)
    {
        $this->route->method($method);
        return $this;
    }

    /**
     * Sets the end point handler
     *
     * @param callable $callable Handle function
     *
     * @return Router
     */
    public function handler($callable)
    {
        $this->route->handler($callable);
        return $this;
    }

    /**
     * Defines a parameter to be passed to the handler method
     *
     * @param string                    $param      Parameter name
     * @param string|ValidatorInterface $validation Validation
     *
     * @return Router
     */
    public function param($param, $validation = null)
    {
        $this->route->param($param, $validation);
        return $this;
    }

    /**
     * Defines an argument to be passed to the handler method
     *
     * @param mixed $arg Argument to pass
     *
     * @return Router
     */
    public function arg($arg)
    {
        $this->route->arg($arg);
        return $this;
    }

    /**
     * Handles routes
     *
     * @param string $url    URL being accessed
     * @param string $method HTTP request method
     * @param array  $params URL parameters
     *
     * @throws \Exception
     *
     * @return string
     */
    public function run($url, $method, $params)
    {
        foreach ($this->routes as $route) {
            if ($route->isMatch($url, $method)) {
                return $route->exec($params);
            }
        }

        throw new \Exception("$method:$url matches no defined route");
    }
}
