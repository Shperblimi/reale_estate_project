<?php
require_once __DIR__ . '/../config/cors.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../services/AuthService.php';

$conn    = getConnection();
$service = new AuthService($conn);
$method  = $_SERVER['REQUEST_METHOD'];
$action  = $_GET['action'] ?? '';

try {
    // GET /AuthController.php?action=me — returns the logged-in user's profile
    if ($method === 'GET' && $action === 'me') {
        $payload = authenticate();
        echo json_encode($service->me($payload['sub']));
        exit();
    }

    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit();
    }

    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    switch ($action) {
        case 'register':
            $result = $service->register($data);
            http_response_code(201);
            echo json_encode(['success' => true] + $result);
            break;

        case 'login':
            $result = $service->login($data);
            echo json_encode(['success' => true] + $result);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unknown action']);
    }
} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} catch (RuntimeException $e) {
    // 409 for duplicate email, 401 for bad credentials, 404 for not found
    $msg = $e->getMessage();
    if (str_contains($msg, 'already registered')) {
        http_response_code(409);
    } elseif (str_contains($msg, 'Invalid credentials')) {
        http_response_code(401);
    } else {
        http_response_code(404);
    }
    echo json_encode(['error' => $msg]);
}

$conn->close();
