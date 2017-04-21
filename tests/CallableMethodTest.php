<?php
require_once __DIR__ . "/../src/CallableMethod.php";

use \PhilWaters\API\CallableMethod;

class CallableMethodTest extends PHPUnit_Framework_TestCase
{
    public function testFunction()
    {
        $callableMethod = new CallableMethod("str_replace");

        $this->assertEquals("barbar", $callableMethod->call(array("foo", "bar", "foobar")));
    }

    public function testClassMethod()
    {
        $callableMethod = new CallableMethod(array("DateTime", "setDate"));

        $dateTime = $callableMethod->call(array(2017, 4, 1));

        $this->assertEquals("2017-04-01", $dateTime->format("Y-m-d"));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage this_does_not_exist is not callable
     */
    public function testNonExistentFunction()
    {
        $callableMethod = new CallableMethod("this_does_not_exist");
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage ThisDoesNotExist does not exist
     */
    public function testNonExistentClass()
    {
        $callableMethod = new CallableMethod(array("ThisDoesNotExist", "test"));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Method DateTime::thisDoesNotExist() does not exist
     */
    public function testNonExistentClassMethod()
    {
        $callableMethod = new CallableMethod(array("DateTime", "thisDoesNotExist"));
    }

    public function testClosure()
    {
        $format= "A: %d, B: %s, C: %s";
        $callableMethod = new CallableMethod(function($a, $b, $c) use ($format) {
            return sprintf($format, $a, $b, $c);
        });

        $this->assertEquals("A: 1, B: 2, C: 3", $callableMethod->call(array(1, 2, 3)));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalid()
    {
        $callableMethod = new CallableMethod(10);
    }
}
