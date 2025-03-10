<?php



$path = $_SERVER['DOCUMENT_ROOT'] . "/";


require_once 'vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable("/var");
$dotenv->load();



#region Tokens verification

$headers = apache_request_headers();
$authTokenHashed = isset($headers['Authorization']) ? $headers['Authorization'] : null;

$readOnlyToken = $_ENV['READ_ONLY_TOKEN'];
$writeToken = $_ENV['WRITE_TOKEN'];
$adminToken = $_ENV['ADMIN_TOKEN'];



if ($authTokenHashed && password_verify($readOnlyToken, $authTokenHashed)) {
    $authorizations = "readonly";
} elseif ($authTokenHashed && password_verify($writeToken, $authTokenHashed)) {
    $authorizations = "write";
} elseif ($authTokenHashed && password_verify($adminToken, $authTokenHashed)) {
    $authorizations = "admin";
} else {
    echo json_encode(["message" => "Unauthorized: Invalid or missing token"]);
    http_response_code(401); // Unauthorized
    exit;
}

#endregion


#region Parameter validation



$table = isset($_GET['table']) ? $_GET['table'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;

$validTables = [
    'route', 'routes',
    'route_status',
    'sector', 'sectors',
    'wall', 'walls',
    'user', 'users'
];

if (!in_array($table, $validTables)) {
    echo json_encode(["message" => "Invalid table parameter"]);
    exit;
}

if ($table != "route_status") {
    $table = rtrim($table, 's');
}

#endregion

#region HTTP method and Token verification
$method = $_SERVER['REQUEST_METHOD'];

// if not a GET method
if ($method !== 'GET') {

    // get body data
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if ($authorizations == "write" && $table == "route_status") {
        // continue
    } elseif ($authorizations == "admin") {
        // continue
    } else {
        echo json_encode(["message" => "Unauthorized: Invalid or missing authorization"]);
        http_response_code(403); // Forbidden
        exit;
    }

}




#endregion


#region Database connection


require_once $path . '/api/config/database.php';
$database = new Database();
$db = $database->getConnection();

#endregion



#region Determine the HTTP method
$method = $_SERVER['REQUEST_METHOD'];


switch ($table) {
    case 'route':
        require_once $path . '/api/controllers/RouteController.php';
        $controller = new RouteController($db);
        break;

    case 'wall':
        require_once $path . '/api/controllers/WallController.php';
        $controller = new WallController($db);
        break;

    case 'sector':
        require_once $path . '/api/controllers/SectorController.php';
        $controller = new SectorController($db);
        break;

    case 'user':
        require_once $path . '/api/controllers/UserController.php';
        $controller = new UserController($db);
        break;

    case 'route_status':
        require_once $path . '/api/controllers/Route_StatusController.php';
        $controller = new Route_StatusController($db);
        break;

    default:
        echo json_encode(["message" => "Invalid request"]);
        exit;
}

#endregion

#region CRUD on HTTP method
switch ($method) {
    case 'GET':
        if ($id || $table == "route_status") {
            $controller->getOne();
        } else {
            $controller->getAll();
        }
        break;

    case 'POST':
        $controller->create();
        break;

    case 'PUT':
        if ($id || $table == "route_status") {

            $controller->update();
        } else {
            echo json_encode(["message" => "ID is required for updating"]);
        }
        break;

    case 'DELETE':
        if ($id || $table == "route_status") {
            $controller->delete();
        } else {
            echo json_encode(["message" => "ID is required for deleting"]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}
#endregion