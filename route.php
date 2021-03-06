<?php
require_once ("classes/controllers/Controller.php");

function error($message, $code = 500) {
    http_response_code($code);
    exit($message);
}


$urlParts = explode('/' , $_SERVER['REQUEST_URI']);
$id = array_pop($urlParts);
$controller = array_pop($urlParts);

if (!$controller) {
    die('Nope');
}


$method = $_SERVER['REQUEST_METHOD'];
if (!$controller = Controller::load($controller)) {
    error("No such resource", 404);
}

switch ($method) {
    case 'POST':
        $action = !empty($id) ? "update" : "insert";
        break;
    case 'GET':
        $action = !empty($id) ? "get" : "get_all";
        break;
    default:
        error("Invalid request method", 400);
        break;
}

if(!method_exists($controller, $action)) {
    error("Method not found", 405);
}

$result = $controller->$action($id, $_REQUEST);
if (is_null($result)) {
    error("Record not found", 404);
}

header("Content-type: application/json; charset=utf-8");
echo json_encode($result);


