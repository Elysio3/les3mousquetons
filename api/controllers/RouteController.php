<?php

require_once $path . '/api/models/Route.php'; // Include the model

class RouteController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all routes
    public function getAll() {
        $route = new Route($this->conn);  // Use the Route model
        $result = $route->getAll();  // Call the model's method to get all routes
        echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
    }

    // Get one route by ID
    public function getOne() {
        $route = new Route($this->conn);  // Use the Route model
        $route->id = $_GET['id'];  // Set the ID in the model
        $result = $route->getOne();  // Call the model's method to get the route by ID
        echo json_encode($result->fetch(PDO::FETCH_ASSOC));
    }

    // Create a new route
    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        $route = new Route($this->conn);  // Use the Route model
        $route->name = $data->name;
        $route->route_setter_id = $data->route_setter_id;
        $route->sector_id = $data->sector_id;
        $route->difficulty = $data->difficulty;
        $route->color = $data->color;
        $route->image_url = $data->image_url;

        if ($route->create()) {
            echo json_encode(["message" => "Route created successfully"]);
        } else {
            echo json_encode(["message" => "Failed to create route"]);
        }
    }

    // Update a route
    public function update() {
        $data = json_decode(file_get_contents("php://input"));
        $route = new Route($this->conn);  // Use the Route model
        $route->id = $_GET['id'];
        $route->name = $data->name;
        $route->sector_id = $data->sector_id;
        $route->difficulty = $data->difficulty;
        $route->color = $data->color;
        $route->image_url = $data->image_url;

        if ($route->update()) {
            echo json_encode(["message" => "Route updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update route"]);
        }
    }

    // Delete a route
    public function delete() {
        $route = new Route($this->conn);  // Use the Route model
        $route->id = $_GET['id'];

        if ($route->delete()) {
            echo json_encode(["message" => "Route deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete route"]);
        }
    }
}
