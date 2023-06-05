<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-API-KEY, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Headers, Authorization, observe, enctype, Content-Length, X-Csrf-Token");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");
header('content-type: application/json; charset=utf-8');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header("HTTP/1.1 200 OK CORS");
    die();
}


parse_str($_SERVER['QUERY_STRING'], $query);
$body = json_decode(file_get_contents('php://input'), false);

try {
    // Récupération des variables.
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $id = isset($query['id']) ? (int) $query['id'] : '';
    $body = isset($body) ? $body : '';

    // Appel des méthodes souhaitées.
    switch($method) {
        case 'GET':
            if ($id) {
                $result = read($id);
            } else {
                $result = search();
            }
            break;
        case 'POST':
            $result = create($body);
            break;
        case 'PUT':
            $result = put($id, $body);
            break;
        case 'PATCH':
            $result = patch($id, $body);
            break;
        case 'DELETE':
            $result = delete($id);
            break;
    }

    echo json_encode($result);

} catch(Exception $e) {

    // convert string code error from PDO in 500 error
    if (gettype($e->getCode()) == "string") {
        $codeError = 500;
    } else {
        $codeError = $e->getCode();
    }

    http_response_code($codeError);
    echo json_encode(['message' => $e->getMessage()]);
}