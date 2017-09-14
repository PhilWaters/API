# API
API router

## Example

$router = new PhilWaters\API\Router();

$router
    ->url("people/(?P<id>[0-9]+).(?P<format>(json|text))")
    ->method("GET")
    ->handler(array("PeopleController", "load"));

$router->run($_GET['url'], $_SERVER['REQUEST_METHOD'], $_REQUEST);

-------------------

use PhilWaters\API\BaseController;

class PeopleController extends PhilWaters\API\BaseController
{
    public function load($id, $format)
    {
        $data = array();

        if ($format == "json") {
            $this->respondJSON($data);
        } else {
            $this->respondText(implode(",", $data));
        }
    }
}

-------------------

GET /api/people/7.json HTTP/1.1