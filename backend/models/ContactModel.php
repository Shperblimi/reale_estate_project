<?php
class ContactModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create($data) {
        $name        = $this->conn->real_escape_string($data['name']);
        $email       = $this->conn->real_escape_string($data['email']);
        $phone       = $this->conn->real_escape_string($data['phone'] ?? '');
        $message     = $this->conn->real_escape_string($data['message']);
        $property_id = isset($data['property_id']) ? (int)$data['property_id'] : 'NULL';

        $this->conn->query(
            "INSERT INTO contacts (name, email, phone, message, property_id)
             VALUES ('$name', '$email', '$phone', '$message', $property_id)"
        );
        return $this->conn->insert_id;
    }

    public function findAll() {
        $result = $this->conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function findById($id) {
        $id     = (int)$id;
        $result = $this->conn->query("SELECT * FROM contacts WHERE id = $id");
        return $result->fetch_assoc();
    }

    public function update($id, $data) {
        $id      = (int)$id;
        $fields  = [];
        $allowed = ['name', 'email', 'phone', 'message'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $val = $this->conn->real_escape_string($data[$field]);
                $fields[] = "$field = '$val'";
            }
        }
        if (empty($fields)) return false;
        $set = implode(', ', $fields);
        return $this->conn->query("UPDATE contacts SET $set WHERE id = $id");
    }

    public function delete($id) {
        $id = (int)$id;
        return $this->conn->query("DELETE FROM contacts WHERE id = $id");
    }
}
