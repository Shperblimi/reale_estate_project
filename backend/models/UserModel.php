<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findByEmail($email) {
        $email = $this->conn->real_escape_string($email);
        $result = $this->conn->query("SELECT * FROM users WHERE email = '$email'");
        return $result->fetch_assoc();
    }

    public function findById($id) {
        $id = (int)$id;
        $result = $this->conn->query("SELECT id, name, email, role, created_at FROM users WHERE id = $id");
        return $result->fetch_assoc();
    }

    public function findAll() {
        $result = $this->conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function create($name, $email, $hashedPassword) {
        $name  = $this->conn->real_escape_string($name);
        $email = $this->conn->real_escape_string($email);
        $pass  = $this->conn->real_escape_string($hashedPassword);
        $this->conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')");
        return $this->conn->insert_id;
    }

    public function update($id, $data) {
        $id      = (int)$id;
        $fields  = [];
        $allowed = ['name', 'email', 'role'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $val = $this->conn->real_escape_string($data[$field]);
                $fields[] = "$field = '$val'";
            }
        }
        if (isset($data['password'])) {
            $hashed   = $this->conn->real_escape_string(password_hash($data['password'], PASSWORD_BCRYPT));
            $fields[] = "password = '$hashed'";
        }
        if (empty($fields)) return false;
        $set = implode(', ', $fields);
        return $this->conn->query("UPDATE users SET $set WHERE id = $id");
    }

    public function delete($id) {
        $id = (int)$id;
        return $this->conn->query("DELETE FROM users WHERE id = $id");
    }
}
