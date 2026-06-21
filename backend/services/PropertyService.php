<?php
require_once __DIR__ . '/../models/PropertyModel.php';

class PropertyService {
    private $model;

    public function __construct($conn) {
        $this->model = new PropertyModel($conn);
    }

    public function getAll($filters = []) {
        $allowed = ['city', 'type', 'category'];
        $clean = array_intersect_key($filters, array_flip($allowed));
        return $this->model->findAll($clean);
    }

    public function getById($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('Invalid property ID');
        }
        $property = $this->model->findById($id);
        if (!$property) {
            throw new RuntimeException('Property not found');
        }
        return $property;
    }

    public function create($data) {
        if (empty($data['title'])) {
            throw new InvalidArgumentException('Title is required');
        }
        if (empty($data['price']) || !is_numeric($data['price'])) {
            throw new InvalidArgumentException('A valid price is required');
        }
        if (empty($data['type']) || !in_array($data['type'], ['sale', 'rent'])) {
            throw new InvalidArgumentException('Type must be sale or rent');
        }
        if (empty($data['category']) || !in_array($data['category'], ['house', 'apartment', 'villa', 'land', 'commercial'])) {
            throw new InvalidArgumentException('Invalid category');
        }
        return $this->model->create($data);
    }

    public function update($id, $data) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('Invalid property ID');
        }
        if (!$this->model->findById($id)) {
            throw new RuntimeException('Property not found');
        }
        return $this->model->update($id, $data);
    }

    public function delete($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('Invalid property ID');
        }
        if (!$this->model->findById($id)) {
            throw new RuntimeException('Property not found');
        }
        return $this->model->delete($id);
    }
}
