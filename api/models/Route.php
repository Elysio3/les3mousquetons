<?php

class Route {
    private $conn;
    private $table = 'routes';

    public $id;
    public $name;
    public $route_setter_id;
    public $sector_id;
    public $difficulty;
    public $color;
    public $image_url;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all routes
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get a single route by ID
    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Create a new route
    public function create() {
        $query = "INSERT INTO " . $this->table . " (name, sector_id, route_setter_id, difficulty, color, image_url) VALUES (:name, :sector_id, :route_setter_id, :difficulty, :color, :image_url)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':sector_id', $this->sector_id);
        $stmt->bindParam(':route_setter_id', $this->route_setter_id);
        $stmt->bindParam(':difficulty', $this->difficulty);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':image_url', $this->image_url);

        return $stmt->execute();
    }

    // Update a route
    public function update() {
        $query = "UPDATE " . $this->table . " SET name = :name, sector_id = :sector_id, route_setter_id = :route_setter_id, difficulty = :difficulty, color = :color, image_url = :image_url WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':sector_id', $this->sector_id);
        $stmt->bindParam(':route_setter_id', $this->route_setter_id);
        $stmt->bindParam(':difficulty', $this->difficulty);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a route
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
