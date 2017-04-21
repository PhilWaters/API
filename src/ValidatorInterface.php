<?php
/**
 * ValidatorInterface
 */

namespace PhilWaters\API;

/**
 * Interface to define custom route validators
 */
interface ValidatorInterface
{
    /**
     * Validates route access
     *
     * @param unknown $string
     */
    public function validate($string);
}
