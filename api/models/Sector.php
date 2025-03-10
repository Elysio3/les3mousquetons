<?php

class Sector {
    private $conn;
    private $table = 'sectors';

    public $id;
    public $name;
    public $wall_id;
    public $image_url;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all sectors
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get a single sector by ID
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Create a new sector
    public function create() {
        $query = "INSERT INTO " . $this->table . " (name, wall_id, image_url) VALUES (:name, :wall_id, :image_url)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':wall_id', $this->wall_id);
        $stmt->bindParam(':image_url', $this->image_url);

        return $stmt->execute();
    }

    // Update a sector
    public function update() {
        $query = "UPDATE " . $this->table . " SET name = :name, wall_id = :wall_id, image_url = :image_url WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':wall_id', $this->wall_id);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a sector
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
