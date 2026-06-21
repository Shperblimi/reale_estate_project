<?php
class PropertyModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findAll($filters = []) {
        $where = "WHERE 1=1";
        if (!empty($filters['city'])) {
            $city = $this->conn->real_escape_string($filters['city']);
            $where .= " AND city = '$city'";
        }
        if (!empty($filters['type'])) {
            $type = $this->conn->real_escape_string($filters['type']);
            $where .= " AND type = '$type'";
        }
        if (!empty($filters['category'])) {
            $category = $this->conn->real_escape_string($filters['category']);
            $where .= " AND category = '$category'";
        }
        $result = $this->conn->query("SELECT * FROM properties $where ORDER BY created_at DESC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function findById($id) {
        $id = (int)$id;
        $result = $this->conn->query("SELECT * FROM properties WHERE id = $id");
        return $result->fetch_assoc();
    }

    public function create($data) {
        $title       = $this->conn->real_escape_string($data['title']);
        $description = $this->conn->real_escape_string($data['description'] ?? '');
        $price       = (float)$data['price'];
        $type        = $this->conn->real_escape_string($data['type']);
        $category    = $this->conn->real_escape_string($data['category']);
        $bedrooms    = (int)($data['bedrooms'] ?? 0);
        $bathrooms   = (int)($data['bathrooms'] ?? 0);
        $area        = (float)($data['area'] ?? 0);
        $address     = $this->conn->real_escape_string($data['address'] ?? '');
        $city        = $this->conn->real_escape_string($data['city'] ?? '');
        $country     = $this->conn->real_escape_string($data['country'] ?? '');
        $agent_id    = isset($data['agent_id']) ? (int)$data['agent_id'] : 'NULL';

        $sql = "INSERT INTO properties
                    (title, description, price, type, category, bedrooms, bathrooms, area, address, city, country, agent_id)
                VALUES
                    ('$title', '$description', $price, '$type', '$category', $bedrooms, $bathrooms, $area, '$address', '$city', '$country', $agent_id)";

        $this->conn->query($sql);
        return $this->conn->insert_id;
    }

    public function update($id, $data) {
        $id     = (int)$id;
        $fields = [];
        $allowed = ['title', 'description', 'price', 'type', 'category', 'bedrooms', 'bathrooms', 'area', 'address', 'city', 'country', 'status'];
        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $val = $this->conn->real_escape_string($data[$field]);
                $fields[] = "$field = '$val'";
            }
        }
        if (empty($fields)) return false;
        $set = implode(', ', $fields);
        return $this->conn->query("UPDATE properties SET $set WHERE id = $id");
    }

    public function delete($id) {
        $id = (int)$id;
        return $this->conn->query("DELETE FROM properties WHERE id = $id");
    }
}
