<?php

require_once $path . '/api/models/Route_Status.php'; // Include the model

class Route_StatusController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all route_status
    public function getAll() {
        $route_status = new Route_Status($this->conn);  // Use the Route_Status model
        $result = $route_status->getAll();  // Call the model's method to get all routes
        echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
    }

    // Get one route_status by user_id and route_id
    public function getOne() {
        $route_status = new Route_Status($this->conn);  // Use the Route_Status model
        $route_status->user_id = $_GET['user_id'];
        $route_status->route_id = $_GET['route_id'];
        $result = $route_status->getOne();  // Call the model's method to get one route
        echo json_encode($result->fetch(PDO::FETCH_ASSOC));
    }

    // Create a new route_status
    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        $route_status = new Route_Status($this->conn);  // Use the Route_Status model
        $route_status->user_id = $data->user_id;
        $route_status->route_id = $data->route_id;
        $route_status->status = $data->status;
        $route_status->favorite = $data->favorite;

        if ($route_status->create()) {
            echo json_encode(["message" => "Route_status created successfully"]);
        } else {
            echo json_encode(["message" => "Failed to create route_status"]);
        }
    }

    // Update a route_status
    public function update() {
        try {
            $route_status = new Route_Status($this->conn); // Use the Route_Status model

            // Get input data
            $data = json_decode(file_get_contents("php://input"));

            // Assign user_id and route_id
            $route_status->user_id = $data->user_id ?? null;
            $route_status->route_id = $data->route_id ?? null;

            // Ensure user_id and route_id are provided
            if (!$route_status->user_id || !$route_status->route_id) {
                echo json_encode(["message" => "Invalid user_id or route_id"]);
                exit;
            }

            // Handle actions
            if ($data->action == "unfavorite") {
                $route_status->favorite = 0;
            } elseif ($data->action == "favorite") {
                $route_status->favorite = 1;

            } elseif ($data->action == "null") {
                $route_status->status = "null";
            } elseif ($data->action == "project") {
                $route_status->status = "project";
            } elseif ($data->action == "completed") {
                $route_status->status = "completed";
            } else {
                echo json_encode(["message" => "Invalid action"]);
                exit;
            }

            // Attempt to update the route status
            if ($route_status->update()) {
                echo json_encode(["message" => "Route_status updated successfully"]);
                exit;
            } else {
                echo json_encode(["message" => "Failed to update route_status"]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(["message" => "Failed to update route_status", "error" => $e->getMessage()]);
            exit;
        }
    }


    // Delete a route_status
    public function delete() {
        $route_status = new Route_Status($this->conn);  // Use the Route_Status model

        $data = json_decode(file_get_contents("php://input"));
        $route_status->user_id = $data->user_id;
        $route_status->route_id = $data->route_id;

        if ($route_status->delete()) {
            echo json_encode(["message" => "Route_status deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete route_status"]);
        }
    }
}
