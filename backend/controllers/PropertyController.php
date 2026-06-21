<?php
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../services/PropertyService.php';

$conn    = getConnection();
$service = new PropertyService($conn);
$method  = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                echo json_encode($service->getById($_GET['id']));
            } else {
                echo json_encode($service->getAll($_GET));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $id   = $service->create($data);
            http_response_code(201);
            echo json_encode(['success' => true, 'id' => $id]);
            break;

        case 'PUT':
            $id   = $_GET['id'] ?? null;
            $data = json_decode(file_get_contents('php://input'), true);
            $service->update($id, $data);
            echo json_encode(['success' => true]);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            $service->delete($id);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} catch (RuntimeException $e) {
    http_response_code(404);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
