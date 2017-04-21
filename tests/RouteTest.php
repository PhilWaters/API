<?php
require_once __DIR__ . "/../src/Route.php";

use \PhilWaters\API\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $route = new Route("");
    }
}
