<?php
/**
 * CallableMethod class
 */

namespace PhilWaters\API;

/**
 * Handles callable functions, closures and class methods
 */
class CallableMethod
{
    /**
     * Stores instances of callable functions, closures and class methods
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Stores key of callable function, closure and class method
     *
     * @var string
     */
    private $key;

    /**
     * CallableMethod constructor
     *
     * @param Callable $callable Callable function, closure or class method
     */
    public function __construct($callable)
    {
        $this->key = $this->getKey($callable);

        if (!isset(self::$instances[$this->key])) {
            self::$instances[$this->key] = $this->parse($callable);
        }
    }

    /**
     * Calls callable function, closure or class method
     *
     * @param $arguments array[optional] Arguments to pass to function, closure or class method
     *
     * @return mixed
     */
    public function call($arguments = array())
    {
        return call_user_func_array(self::$instances[$this->key], $arguments);
    }

    /**
     * Parses array or string into something which is callable
     *
     * @param array|string|Closure $callable Callable function, closure or class method
     *
     * @throws \InvalidArgumentException
     *
     * @return callable
     */
    private function parse($callable)
    {
        if (is_array($callable)) {
            $className = $callable[0];
            $method = $callable[1];

            if (!class_exists($className)) {
                throw new \InvalidArgumentException("$className does not exist");
            }

            if (!$this->isStaticMethod($className, $method)) {
                $callable = array(new $className(), $method);
            }

            return $callable;
        }

        if (is_callable($callable)) {
            return $callable;
        }

        throw new \InvalidArgumentException("{$this->key} is not callable");
    }

    /**
     * Converset callable into string key
     *
     * @param array|string|Closure $callable Callable function, closure or class method
     *
     * @return string
     */
    private function getKey($callable)
    {
        if (is_array($callable)) {
            return implode("::", $callable);
        }

        if (is_string($callable)) {
            return $callable;
        }

        if (function_exists("spl_object_hash") && is_object($callable)) {
            return spl_object_hash($callable);
        }

        return md5(print_r($callable, true));
    }

    /**
     * Checks whether or nor a class method is static
     *
     * @param string $className Class name
     * @param string $method    Method name
     *
     * @throws \InvalidArgumentException
     *
     * @return boolean
     */
    private function isStaticMethod($className, $method)
    {
        try {
            $methodChecker = new \ReflectionMethod($className, $method);
        } catch (\ReflectionException $ex) {
            throw new \InvalidArgumentException($ex->getMessage(), $ex->getCode());
        }

        return $methodChecker->isStatic();
    }
}
