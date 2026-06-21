<?php
require_once __DIR__ . '/../models/ContactModel.php';

class ContactService {
    private $model;

    public function __construct($conn) {
        $this->model = new ContactModel($conn);
    }

    public function send($data) {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Name is required');
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('A valid email is required');
        }
        if (empty($data['message'])) {
            throw new InvalidArgumentException('Message is required');
        }
        $id = $this->model->create($data);
        return ['id' => $id, 'message' => 'Message sent successfully'];
    }

    public function getAll() {
        return $this->model->findAll();
    }
}
