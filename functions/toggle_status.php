<?php

session_start();



$path = $_SERVER['DOCUMENT_ROOT'];

require_once $path . '/vendor/autoload.php';

include $path . '/functions/http_functions.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable("/var");
$dotenv->load();



if (isset($_SESSION['user'])) {

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['method'])
        && ($_POST['method'] === 'toggle_status'
            || $_POST['method'] === 'toggle_favorite')) {

        //$user_id = $_SESSION['user_id'];
        $route_id = isset($_POST['route_id']) ? intval($_POST['route_id']) : null;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
        $action = isset($_POST['action']) ? $_POST['action'] : null;


        // Prepare data for API call
        $data = [
            'user_id' => $user_id,
            'route_id' => $route_id,
            'action' => $action
        ];

        $result = putData('route_status', $data);
        echo json_encode($result);
    }
}