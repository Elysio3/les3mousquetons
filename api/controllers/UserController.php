<?php

require_once $path . '/api/models/User.php'; // Include the model

class UserController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all users
    public function getAll() {
        $user = new User($this->conn);  // Use the User model
        $result = $user->getAll();  // Call the model's method to get all users
        echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
    }

    // Get one user by ID
    public function getOne() {
        $user = new User($this->conn);  // Use the User model
        $user->id = $_GET['id'];  // Set the ID in the model
        $result = $user->getOne();  // Call the model's method to get the user by ID
        echo json_encode($result->fetch(PDO::FETCH_ASSOC));
    }

    // Create a new user
    public function create() {
        $data = json_decode(file_get_contents("php://input"));
        $user = new User($this->conn);  // Use the User model
        $user->username = $data->username;
        $user->email = $data->email;
        $user->password_hashed = password_hash($data->password, PASSWORD_DEFAULT);

        if ($user->create()) {
            echo json_encode(["message" => "User created successfully"]);
        } else {
            echo json_encode(["message" => "Failed to create user"]);
        }
    }

    // Update a user
    public function update() {
        $data = json_decode(file_get_contents("php://input"));
        $user = new User($this->conn);  // Use the User model
        $user->id = $_GET['id'];
        $user->username = $data->username;
        $user->email = $data->email;

        if ($user->update()) {
            echo json_encode(["message" => "User updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update user"]);
        }
    }

    // Delete a user
    public function delete() {
        $user = new User($this->conn);  // Use the User model
        $user->id = $_GET['id'];

        if ($user->delete()) {
            echo json_encode(["message" => "User deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete user"]);
        }
    }
}
