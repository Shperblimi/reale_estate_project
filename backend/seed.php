<?php
/**
 * Run once after importing database.sql:
 *   php backend/seed.php
 *
 * Creates the admin user with a properly bcrypt-hashed password.
 */

require_once __DIR__ . '/config/database.php';

$conn = getConnection();

$name     = 'Admin';
$email    = 'admin@realestate.com';
$password = 'admin123';
$role     = 'admin';

// Check if admin already exists
$check = $conn->query("SELECT id FROM users WHERE email = '" . $conn->real_escape_string($email) . "'");
if ($check->num_rows > 0) {
    echo "Admin user already exists — skipping.\n";
    $conn->close();
    exit(0);
}

$hashed = password_hash($password, PASSWORD_BCRYPT);
$name   = $conn->real_escape_string($name);
$email  = $conn->real_escape_string($email);
$hash   = $conn->real_escape_string($hashed);

$conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hash', '$role')");

if ($conn->affected_rows === 1) {
    echo "Admin user created.\n";
    echo "  Email:    admin@realestate.com\n";
    echo "  Password: admin123\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

$conn->close();
