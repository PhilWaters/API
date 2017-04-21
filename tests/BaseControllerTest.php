<?php
require_once __DIR__ . "/../src/BaseController.php";

use \PhilWaters\API\BaseController;

class BaseControllerTest extends PHPUnit_Framework_TestCase
{
    private $jpegPath;
    private $pngPath;
    private $gifPath;
    private $baseController;
    public $header;

    public function setUp()
    {
        $this->header = "";
        $self = $this;
        runkit_function_redefine("header", function($text) use ($self) {
            $self->header = $text;
        });
        runkit_function_redefine("http_response_code", function($code) use ($self) {
            $self->header = "$code";
        });

        $this->jpegPath = __DIR__ . "/data/image.jpg";
        $this->pngPath = __DIR__ . "/data/image.png";
        $this->gifPath = __DIR__ . "/data/image.gif";
    }

    public function testRespondCSS_text()
    {
        $baseController = new BaseController();
        $css = ".test{margin:0}";
        $this->expectOutputString($css);

        $baseController->respondCSS($css);
        $this->assertContains("text/css", $this->header);
    }

    public function testRespondCSS_path()
    {
        $baseController = new BaseController();
        $path = __DIR__ . "/data/test.css";
        $this->expectOutputString(file_get_contents($path));

        $baseController->respondCSS($path);
        $this->assertContains("text/css", $this->header);
    }

    public function testRespondJavaScript_text()
    {
        $baseController = new BaseController();
        $js = "function test(){return 123;}";
        $this->expectOutputString($js);

        $baseController->respondJavaScript($js);
        $this->assertContains("text/javascript", $this->header);
    }

    public function testRespondJavaScript_path()
    {
        $baseController = new BaseController();
        $path = __DIR__ . "/data/test.js";
        $this->expectOutputString(file_get_contents($path));

        $baseController->respondJavaScript($path);
        $this->assertContains("text/javascript", $this->header);
    }

    public function testRespondJPEG_data()
    {
        $baseController = new BaseController();
        $jpeg = file_get_contents($this->jpegPath);
        $this->expectOutputString($jpeg);

        $baseController->respondJPEG($jpeg);
        $this->assertContains("image/jpeg", $this->header);
    }

    public function testRespondJPEG_file()
    {
        $baseController = new BaseController();
        $this->expectOutputString(file_get_contents($this->jpegPath));

        $baseController->respondJPEG($this->jpegPath);
        $this->assertContains("image/jpeg", $this->header);
    }

    public function testRespondJPEG_resource()
    {
        $baseController = new BaseController();
        $image = imagecreatefromjpeg($this->jpegPath);
        $this->expectOutputRegex("/JFIF/");

        $baseController->respondJPEG($image);
        $this->assertContains("image/jpeg", $this->header);
    }

    public function testRespondPNG_data()
    {
        $baseController = new BaseController();
        $png = file_get_contents($this->pngPath);
        $this->expectOutputString($png);

        $baseController->respondPNG($png);
        $this->assertContains("image/png", $this->header);
    }

    public function testRespondPNG_file()
    {
        $baseController = new BaseController();
        $this->expectOutputString(file_get_contents($this->pngPath));

        $baseController->respondPNG($this->pngPath);
        $this->assertContains("image/png", $this->header);
    }

    public function testRespondPNG_resource()
    {
        $baseController = new BaseController();
        $image = imagecreatefrompng($this->pngPath);
        $this->expectOutputRegex("/.+/");

        $baseController->respondPNG($image);
        $this->assertContains("image/png", $this->header);
    }

    public function testRespondGIF_data()
    {
        $baseController = new BaseController();
        $gif = file_get_contents($this->gifPath);
        $this->expectOutputString($gif);

        $baseController->respondGIF($gif);
        $this->assertContains("image/gif", $this->header);
    }

    public function testRespondGIF_file()
    {
        $baseController = new BaseController();
        $this->expectOutputString(file_get_contents($this->gifPath));

        $baseController->respondGIF($this->gifPath);
        $this->assertContains("image/gif", $this->header);
    }

    public function testRespondGIF_resource()
    {
        $baseController = new BaseController();
        $image = imagecreatefromgif($this->gifPath);
        $this->expectOutputRegex("/.+/");

        $baseController->respondGIF($image);
        $this->assertContains("image/gif", $this->header);
    }

    public function testRespondJSON_array()
    {
        $baseController = new BaseController();
        $json = array(1,2,3);
        $this->expectOutputString(json_encode($json));

        $baseController->respondJSON($json);
        $this->assertContains("application/json", $this->header);
    }

    public function testRespondJSON_text()
    {
        $baseController = new BaseController();
        $json = "[1,2,3]";
        $this->expectOutputString(json_encode($json));

        $baseController->respondJSON($json);
        $this->assertContains("application/json", $this->header);
    }

    public function testRespondPDF()
    {
        $baseController = new BaseController();
        $pdf = "html{margin:0}";
        $this->expectOutputString($pdf);

        $baseController->respondPDF($pdf);
        $this->assertContains("application/pdf", $this->header);
    }

    public function testRespondRSS()
    {
        $baseController = new BaseController();
        $rss = "html{margin:0}";
        $this->expectOutputString($rss);

        $baseController->respondRSS($rss);
        $this->assertContains("application/rss+xml; charset=ISO-8859-1", $this->header);
    }

    public function testRespondText()
    {
        $baseController = new BaseController();
        $text = "html{margin:0}";
        $this->expectOutputString($text);

        $baseController->respondText($text);
        $this->assertContains("text/plain", $this->header);
    }

    public function testRespondXML_text()
    {
        $baseController = new BaseController();
        $xml = '<?xml version="1.0" encoding="UTF-8"?><test>123</test>';
        $this->expectOutputString($xml);

        $baseController->respondXML($xml);
        $this->assertContains("text/xml", $this->header);
    }

    public function testRespondXML_SimpleXMLElement()
    {
        $baseController = new BaseController();
        $xml = new SimpleXMLElement("<test>123</test>");
        $this->expectOutputString($xml->asXML());

        $baseController->respondXML($xml);
        $this->assertContains("text/xml", $this->header);
    }

    public function testRespondXML_DOMDocument()
    {
        $baseController = new BaseController();
        $doc = new \DOMDocument();
        $this->expectOutputString($doc->saveXML());

        $baseController->respondXML($doc);
        $this->assertContains("text/xml", $this->header);
    }

    public function testRespondError_withHttpResponseCodeFunction()
    {
        $baseController = new BaseController();

        $baseController->respondError(400);
        $this->assertContains("400", $this->header);
    }

    public function testRespondError_withoutHttpResponseCodeFunction()
    {
        $httpStatusCodes = array(
            100,
            101,
            200,
            201,
            202,
            203,
            204,
            205,
            206,
            300,
            301,
            302,
            303,
            304,
            305,
            400,
            401,
            402,
            403,
            404,
            405,
            406,
            407,
            408,
            409,
            410,
            411,
            412,
            413,
            414,
            415,
            500,
            501,
            502,
            503,
            504,
            505
        );

        runkit_function_rename("http_response_code", "http_response_code_backup");

        $baseController = new BaseController();

        foreach ($httpStatusCodes as $httpStatusCode) {
            $baseController->respondError($httpStatusCode);
            $this->assertContains("$httpStatusCode", $this->header);
        }

        runkit_function_rename("http_response_code_backup", "http_response_code");
    }
}
