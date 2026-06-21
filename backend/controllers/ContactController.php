<?php
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../services/ContactService.php';

$conn    = getConnection();
$service = new ContactService($conn);
$method  = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            $data   = json_decode(file_get_contents('php://input'), true);
            $result = $service->send($data);
            http_response_code(201);
            echo json_encode(['success' => true] + $result);
            break;

        case 'GET':
            echo json_encode($service->getAll());
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
