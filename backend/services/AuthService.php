<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/jwt.php';

class AuthService {
    private $model;

    public function __construct($conn) {
        $this->model = new UserModel($conn);
    }

    public function register($data) {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Name is required');
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('A valid email is required');
        }
        if (empty($data['password']) || strlen($data['password']) < 6) {
            throw new InvalidArgumentException('Password must be at least 6 characters');
        }
        if ($this->model->findByEmail($data['email'])) {
            throw new RuntimeException('Email already registered');
        }

        $hashed = password_hash($data['password'], PASSWORD_BCRYPT);
        $id     = $this->model->create($data['name'], $data['email'], $hashed);

        return ['id' => $id, 'message' => 'Registered successfully'];
    }

    public function login($data) {
        if (empty($data['email']) || empty($data['password'])) {
            throw new InvalidArgumentException('Email and password are required');
        }

        $user = $this->model->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            throw new RuntimeException('Invalid credentials');
        }

        $token = jwtEncode([
            'sub'  => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ]);

        unset($user['password']);

        return [
            'token' => $token,
            'user'  => [
                'id'   => $user['id'],
                'name' => $user['name'],
                'email'=> $user['email'],
                'role' => $user['role'],
            ],
        ];
    }

    public function me($userId) {
        $user = $this->model->findById($userId);
        if (!$user) {
            throw new RuntimeException('User not found');
        }
        return $user;
    }
}
