<?php

class Route_Status {
    private $conn;
    private $table = 'route_status';

    public $user_id;
    public $route_id;
    public $status;
    public $favorite;
    public $created_at;
    public $last_edit;


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
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND route_id = :route_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':route_id', $this->route_id);
        $stmt->execute();
        return $stmt;

    }

    // Create a new route
    public function create() {
        $query = "INSERT INTO " . $this->table . " (user_id, route_id, status, favorite, created_at, last_edit) VALUES (:user_id, :route_id, :status, :favorite, :created_at, :last_edit)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':route_id', $this->route_id);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':favorite', $this->favorite);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':last_edit', $this->last_edit);

        return $stmt->execute();
    }

    // Update a route
    public function update() {
        // Start with the base query
        $query = "UPDATE " . $this->table . " SET";

        $updates = [];
        $params = [];

        // Define valid status values (based on your database enum)
        $validStatusValues = ['null', 'project', 'completed'];

        // Dynamically add fields that are set
        if (isset($this->status)) {
            if (in_array($this->status, $validStatusValues)) {
                $updates[] = "status = :status";
                $params[':status'] = $this->status;
            } else {
                throw new Exception("Invalid status value: " . $this->status);
            }
        }

        if (isset($this->favorite)) {
            $updates[] = "favorite = :favorite";
            $params[':favorite'] = (int) $this->favorite; // Ensure favorite is treated as an integer
        }

        // Ensure there are fields to update
        if (empty($updates)) {
            return false; // Nothing to update
        }

        $query .= " " . implode(", ", $updates);
        $query .= " WHERE user_id = :user_id AND route_id = :route_id";

        $params[':user_id'] = $this->user_id;
        $params[':route_id'] = $this->route_id;

        // Prepare and execute the statement
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }




    // Delete a route
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND route_id = :route_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':route_id', $this->route_id);

        return $stmt->execute();
    }
}
