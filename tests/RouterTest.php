<?php
require_once __DIR__ . "/../src/Router.php";

use \PhilWaters\API\Router;

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $router = new Router();

        $router
            ->url("test")
            ->arg("a")
            ->arg("b")
            ->param("param1")
            ->method("POST")
            ->handler("str_replace");

        $this->assertEquals("bbc", $router->run("test", "POST", array("param1" => "abc")));
    }

    public function testRun_nonExistentParam()
    {
        $router = new Router();

        $router
            ->url("test")
            ->arg("a")
            ->arg("b")
            ->param("param1")
            ->method("GET")
            ->handler("str_replace");

        $this->assertEquals("", $router->run("test", "GET", array()));
    }

    public function testRun_urlArgs()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("param1")
            ->method("GET")
            ->handler("str_replace");

        $this->assertEquals("abb", $router->run("replace/c/b", "GET", array("param1" => "abc")));
    }

    /**
     * @expectedException \Exception
     */
    public function testRun_noMatchingURL()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("param1")
            ->method("GET")
            ->handler("str_replace");

        $router->run("test", "GET", array("param1" => "abc"));
    }
}
