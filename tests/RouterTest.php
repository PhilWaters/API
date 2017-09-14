<?php
require_once __DIR__ . "/../src/Router.php";

use \PhilWaters\API\Router;
use PhilWaters\API\Validator;

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
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

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
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("", $router->run("test", "GET", array()));
    }

    public function testRun_urlArgs()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("param1")
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

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
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("test", "GET", array("param1" => "abc"));
    }

    public function testValidation_email_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("email", Validator::EMAIL)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("b@b.com", $router->run("replace/a/b", "GET", array("email" => "a@b.com")));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_email_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("email", Validator::EMAIL)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("email" => "not an email address"));
    }

    public function testValidation_integer_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([0-9]+)/([0-9]+)")
            ->param("integer", Validator::INTEGER)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("8", $router->run("replace/7/8", "GET", array("integer" => "7")));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_integer_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("integer", Validator::INTEGER)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("integer" => "1.7"));
    }

    public function testValidation_float_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([0-9.]+)/([0-9]+)")
            ->param("float", Validator::FLOAT)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("127", $router->run("replace/./2", "GET", array("float" => "1.7")));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_float_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("float", Validator::FLOAT)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("float" => "not a float"));
    }

    public function testValidation_number_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([0-9.]+)/([0-9]+)")
            ->param("number", Validator::NUMBER)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("127", $router->run("replace/./2", "GET", array("number" => "1.7")));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_number_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("number", Validator::NUMBER)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("number" => "not a float"));
    }

    public function testValidation_ip_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([0-9]+)/([0-9]+)")
            ->param("ip", Validator::IP)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("1.2.3.5", $router->run("replace/4/5", "GET", array("ip" => "1.2.3.4")));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_ip_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("ip", Validator::IP)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("ip" => "not an ip address"));
    }

    public function testValidation_boolean_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([0-9]+)")
            ->param("boolean", Validator::BOOLEAN)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("1", $router->run("replace/true/1", "GET", array("boolean" => "true")));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_boolean_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("boolean", Validator::BOOLEAN)
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("boolean" => "not a boolean"));
    }

    public function testValidation_callable_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("test", function($string) {
                return true;
            })
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $this->assertEquals("abb", $router->run("replace/c/b", "GET", array("test" => "abc")));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_callable_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("test", function($string) {
                return false;
            })
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("test" => "abc"));
    }

    public function testValidation_regex_pass()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("test", "`^[a-z]+$`i")
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("test" => "abc"));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_regex_fail()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("test", "`^[0-9]+$`i")
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("test" => "abc"));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidation_regex_invalid()
    {
        $router = new Router();

        $router
            ->url("replace/([a-z]+)/([a-z]+)")
            ->param("test", "`^[a-z]+$")
            ->method("GET")
            ->handler(function($a, $b, $param1, $params) {
                return str_replace($a, $b, $param1);
            });

        $router->run("replace/c/b", "GET", array("test" => "abc"));
    }
}
