<?php
/**
 * BaseController class
 */

namespace PhilWaters\API;

use PhilWaters\Database\Database;

/**
 * API end point controller base class
 */
class BaseController
{
    /**
     * Outputs content type header and string
     *
     * @param string $data        Data to output
     * @param string $contentType Content type of the Database
     *
     * @return void
     */
    public function respond($data, $contentType)
    {
        $this->outputHeaders($contentType);

        echo $data;
    }

    /**
     * Responds with error
     *
     * @param unknown $httpStatusCode
     */
    public function respondError($httpStatusCode)
    {
        if (function_exists("http_response_code")) {
            http_response_code($httpStatusCode);

            return;
        }

        $text = "";
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : "HTTP/1.0";

        switch ($httpStatusCode) {
            case 100: $text = "Continue"; break;
            case 101: $text = "Switching Protocols"; break;
            case 200: $text = "OK"; break;
            case 201: $text = "Created"; break;
            case 202: $text = "Accepted"; break;
            case 203: $text = "Non-Authoritative Information"; break;
            case 204: $text = "No Content"; break;
            case 205: $text = "Reset Content"; break;
            case 206: $text = "Partial Content"; break;
            case 300: $text = "Multiple Choices"; break;
            case 301: $text = "Moved Permanently"; break;
            case 302: $text = "Moved Temporarily"; break;
            case 303: $text = "See Other"; break;
            case 304: $text = "Not Modified"; break;
            case 305: $text = "Use Proxy"; break;
            case 400: $text = "Bad Request"; break;
            case 401: $text = "Unauthorized"; break;
            case 402: $text = "Payment Required"; break;
            case 403: $text = "Forbidden"; break;
            case 404: $text = "Not Found"; break;
            case 405: $text = "Method Not Allowed"; break;
            case 406: $text = "Not Acceptable"; break;
            case 407: $text = "Proxy Authentication Required"; break;
            case 408: $text = "Request Time-out"; break;
            case 409: $text = "Conflict"; break;
            case 410: $text = "Gone"; break;
            case 411: $text = "Length Required"; break;
            case 412: $text = "Precondition Failed"; break;
            case 413: $text = "Request Entity Too Large"; break;
            case 414: $text = "Request-URI Too Large"; break;
            case 415: $text = "Unsupported Media Type"; break;
            case 500: $text = "Internal Server Error"; break;
            case 501: $text = "Not Implemented"; break;
            case 502: $text = "Bad Gateway"; break;
            case 503: $text = "Service Unavailable"; break;
            case 504: $text = "Gateway Time-out"; break;
            case 505: $text = "HTTP Version not supported"; break;
        }

        header("$protocol $httpStatusCode $text");
    }

    /**
     * Outputs content type header
     *
     * @param string $contentType Content type
     *
     * @return void
     */
    protected function outputHeaders($contentType)
    {
        header("Content-Type: $contentType");
    }

    /**
     * Responds with CSS
     *
     * @param string $data CSS string or path
     *
     * @return void
     */
    public function respondCSS($data)
    {
        if (file_exists($data)) {
            $data = file_get_contents($data);
        }

        $this->respond($data, "text/css");
    }

    /**
     * Responds with JavaScript
     *
     * @param string $data JavaScript string or path
     */
    public function respondJavaScript($data)
    {
        if (file_exists($data)) {
            $data = file_get_contents($data);
        }

        $this->respond($data, "text/javascript");
    }

    /**
     * Responds with JPEG
     *
     * @param mixed  $data    Image resource, path or raw JPEG Database
     * @param number $quality Image quality
     *
     * @return void
     */
    public function respondJPEG($data, $quality = 75)
    {
        $contentType = "image/jpeg";

        if (is_resource($data)) {
            $this->outputHeaders($contentType);

            imagejpeg($data, null, $quality);

            return;
        } elseif (@is_file($data)) {
            $data = file_get_contents($data);
        }

        $this->respond($data, $contentType);
    }

    /**
     * Responds with PNG
     *
     * @param mixed  $data    Image resource, path or raw PNG data
     * @param number $quality Image quality
     * @param string $filters PNG filters
     *
     * @return void
     */
    public function respondPNG($data, $quality = 6, $filters = PNG_NO_FILTER)
    {
        $contentType = "image/png";

        if (is_resource($data)) {
            $this->outputHeaders($contentType);

            imagepng($data, null, $quality, $filters);

            return;
        } elseif (@is_file($data)) {
            $data = file_get_contents($data);
        }

        $this->respond($data, $contentType);
    }

    /**
     * Responds with GIF
     *
     * @param mixed $data Image resource, path or raw GIF data
     *
     * @return void
     */
    public function respondGIF($data)
    {
        $contentType = "image/gif";

        if (is_resource($data)) {
            $this->outputHeaders($contentType);

            imagegif($data);

            return;
        } elseif (@is_file($data)) {
            $data = file_get_contents($data);
        }

        $this->respond($data, $contentType);
    }

    /**
     * Responds with JSON
     *
     * @param mixed $data Data to output as JSON
     *
     * @return void
     */
    public function respondJSON($data)
    {
        $data = json_encode($data);

        $this->respond($data, "application/json");
    }

    /**
     * Responds with PDF
     *
     * @param string $data PDF string
     *
     * @return void
     */
    public function respondPDF($data)
    {
        $this->respond($data, "application/pdf");
    }

    /**
     * Responds with RSS
     *
     * @param string $data RSS string
     *
     * @return void
     */
    public function respondRSS($data)
    {
        $this->respond($data, "application/rss+xml; charset=ISO-8859-1");
    }

    /**
     * Responds with text
     *
     * @param string $data Text string
     *
     * @return void
     */
    public function respondText($data)
    {
        $this->respond($data, "text/plain");
    }

    /**
     * Responds with XML
     *
     * @param string|\SimpleXMLElement|\DOMDocument $data XML data as a string, \SimpleXMLElement or \DOMDocument
     *
     * @return void
     */
    public function respondXML($data)
    {
        if ($data instanceof \SimpleXMLElement) {
            $data = $data->asXML();
        } elseif ($data instanceof \DOMDocument) {
            $data = $data->saveXML();
        }

        $this->respond($data, "text/xml");
    }
}
