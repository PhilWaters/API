<?php
/**
 * Validator class
 */

namespace PhilWaters\API;

/**
 * Parameters validator
 */
class Validator implements ValidatorInterface
{
    const EMAIL = "Email";
    const INTEGER = "Integer";
    const NUMBER = "Number";
    const FLOAT = "Float";
    const IP = "IP";
    const BOOLEAN = "Boolean";

    /**
     * Stores validation
     *
     * @var string|ValidatorInterface
     */
    private $validation;

    /**
     * Validator constructor
     *
     * @param string|callable $validation Validation name, regular expression or callable
     */
    public function __construct($validation)
    {
        $this->validation = $validation;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($string)
    {
        $callable = $this->validation;

        if (!is_callable($callable)) {
            $methodName = "validate$callable";

            if (method_exists($this, $methodName)) {
                $callable = array($this, "validate$callable");
            } elseif (@preg_match($callable, null) !== false) {
                return preg_match($callable, $string) === 1;
            } else {
                throw new \InvalidArgumentException("$callable is an unknown validation");
            }
        }

        return call_user_func($callable, $string);
    }

    /**
     * Validates an email address
     *
     * @param string $email Email address
     *
     * @return boolean
     */
    private function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validates an integer
     *
     * @param string $integer Integer
     *
     * @return boolean
     */
    private function validateInteger($integer)
    {
        return filter_var($integer, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validates an number
     *
     * @param string $number Number
     *
     * @return boolean
     */
    private function validateNumber($number)
    {
        return is_numeric($number);
    }

    /**
     * Validates a float
     *
     * @param string $float float
     *
     * @return boolean
     */
    private function validateFloat($float)
    {
        return is_numeric($float);
    }

    /**
     * Validates an IP address
     *
     * @param string $ip IP address
     *
     * @return boolean
     */
    private function validateIP($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validates a boolean
     *
     * @param string $boolean Boolean
     *
     * @return boolean
     */
    private function validateBoolean($boolean)
    {
        return filter_var($boolean, FILTER_VALIDATE_BOOLEAN) !== false;
    }
}
