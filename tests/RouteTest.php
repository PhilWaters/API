<?php
require_once __DIR__ . "/../src/Route.php";

use \PhilWaters\API\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $route = new Route("");

        $route->param("a", "`[a-z]+`i");
        $route->handler(function($a, $params) {
            $this->assertEquals("Bravo", $params['b']);
        });

        $route->exec(array(
            "a" => "Alpha",
            "b" => "Bravo"
        ));
    }
}
