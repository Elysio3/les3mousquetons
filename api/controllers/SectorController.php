<?php

require_once $path . '/api/models/Sector.php'; // Include the model

class SectorController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all sectors
    public function getAll() {
        $sector = new Sector($this->conn);  // Use the Sector model
        $result = $sector->getAll();  // Call the model's method
        echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
    }

    // Get one sector by ID
    public function getOne() {
        $sector = new Sector($this->conn);  // Use the Sector model
        $sector->id = $_GET['id'];
        $result = $sector->getOne();
        echo json_encode($result->fetch(PDO::FETCH_ASSOC));
    }

    // Create a new sector
    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        $sector = new Sector($this->conn);  // Use the Sector model
        $sector->name = $data->name;
        $sector->wall_id = $data->wall_id;
        $sector->image_url = $data->image_url;

        if ($sector->create()) {
            echo json_encode(["message" => "Sector created successfully"]);
        } else {
            echo json_encode(["message" => "Failed to create sector"]);
        }
    }

    // Update a sector
    public function update() {
        $data = json_decode(file_get_contents("php://input"));
        $sector = new Sector($this->conn);  // Use the Sector model
        $sector->id = $_GET['id'];
        $sector->name = $data->name;
        $sector->wall_id = $data->wall_id;
        $sector->image_url = $data->image_url;

        if ($sector->update()) {
            echo json_encode(["message" => "Sector updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update sector"]);
        }
    }

    // Delete a sector
    public function delete() {
        $sector = new Sector($this->conn);  // Use the Sector model
        $sector->id = $_GET['id'];

        if ($sector->delete()) {
            echo json_encode(["message" => "Sector deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete sector"]);
        }
    }
}
