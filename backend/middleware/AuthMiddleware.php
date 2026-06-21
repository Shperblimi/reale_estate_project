<?php
require_once __DIR__ . '/../config/jwt.php';

/**
 * Validates the Bearer token from the Authorization header.
 * Optionally enforces a required role ('user' or 'admin').
 * Exits with 401/403 on failure, returns the token payload on success.
 */
function authenticate(string $requiredRole = null): array {
    $headers = getallheaders();
    $auth    = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (!preg_match('/Bearer\s+(.+)/i', $auth, $matches)) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        exit();
    }

    $payload = jwtDecode($matches[1]);
    if (!$payload) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid or expired token']);
        exit();
    }

    if ($requiredRole !== null && $payload['role'] !== $requiredRole) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: admin access required']);
        exit();
    }

    return $payload;
}
