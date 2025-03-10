<?php

class Wall {
    private $conn;
    private $table = 'walls';

    public $id;
    public $name;
    public $location;
    public $image_url;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all walls
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get a single wall by ID
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Create a new wall
    public function create() {
        $query = "INSERT INTO " . $this->table . " (name, location, image_url) VALUES (:name, :location, :image_url)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':image_url', $this->image_url);

        return $stmt->execute();
    }

    // Update a wall
    public function update() {
        $query = "UPDATE " . $this->table . " SET name = :name, location = :location, image_url = :image_url WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a wall
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
