
<?php

header("Content-Type: application/json");

function response($status, $message, $data = null) {

    http_response_code($status);

    $result = [
        "status" => $status,
        "message" => $message,
        "data" => $data
    ];

    echo json_encode(
        $result,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

$headers = function_exists('getallheaders')
    ? getallheaders()
    : [];

$input = file_get_contents("php://input");

$data = json_decode($input, true);

if (in_array($method, ['POST', 'PUT'])) {

    if (json_last_error() !== JSON_ERROR_NONE) {
        response(400, "Invalid JSON format");
    }

    if (!is_array($data)) {
        response(400, "JSON body must be an object");
    }
}

switch ($method) {

    case 'GET':

        response(200, "GET request successful", [
            "users" => [
                [
                    "id" => 1,
                    "name" => "Osama",
                    "role" => "SOC Analyst"
                ]
            ],
            "headers" => $headers
        ]);

        break;


    case 'POST':

        if (empty($data['name'])) {
            response(400, "Name is required");
        }

        response(201, "User created successfully", [
            "id" => 2,
            "name" => $data['name']
        ]);

        break;


    case 'PUT':

        if (!isset($data['id']) || !isset($data['name'])) {
            response(400, "ID and name are required");
        }

        response(200, "User updated successfully", [
            "id" => $data['id'],
            "name" => $data['name']
        ]);

        break;


    case 'DELETE':

        response(200, "Delete request received successfully", [
            "message" => "User deletion operation simulated"
        ]);

        break;


    default:

        response(405, "HTTP method not allowed");

}
?>
