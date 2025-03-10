<?php

require_once $path . '/api/models/Wall.php'; // Include the model

class WallController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all walls
    public function getAll() {
        $wall = new Wall($this->conn);  // Use the Wall model
        $result = $wall->getAll();  // Call the model's method to get all walls
        echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
    }

    // Get one wall by ID
    public function getOne() {
        $wall = new Wall($this->conn);  // Use the Wall model
        $wall->id = $_GET['id'];  // Set the ID in the model
        $result = $wall->getOne();  // Call the model's method to get the wall by ID
        echo json_encode($result->fetch(PDO::FETCH_ASSOC));
    }

    // Create a new wall
    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        $wall = new Wall($this->conn);  // Use the Wall model
        $wall->name = $data->name;
        $wall->location = $data->location;
        $wall->image_url = $data->image_url;

        if ($wall->create()) {
            echo json_encode(["message" => "Wall created successfully"]);
        } else {
            echo json_encode(["message" => "Failed to create wall"]);
        }
    }

    // Update a wall
    public function update() {
        $data = json_decode(file_get_contents("php://input"));
        $wall = new Wall($this->conn);  // Use the Wall model
        $wall->id = $_GET['id'];
        $wall->name = $data->name;
        $wall->location = $data->location;
        $wall->image_url = $data->image_url;

        if ($wall->update()) {
            echo json_encode(["message" => "Wall updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update wall"]);
        }
    }

    // Delete a wall
    public function delete() {
        $wall = new Wall($this->conn);  // Use the Wall model
        $wall->id = $_GET['id'];

        if ($wall->delete()) {
            echo json_encode(["message" => "Wall deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete wall"]);
        }
    }
}
