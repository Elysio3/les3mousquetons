<!DOCTYPE html>
<html lang="en">


<head>
<?php


include $path.'/views/components/autoload.php';

#region session and header
    if(isset($_SESSION['user'])) {
        include $path.'/views/components/header-online-mobile.php';
    } else {
        include $path.'/views/components/header-offline-mobile.php';
    }
#endregion

#region functions utils
function calculateOpenSince($open_since) {
    $open_date = new DateTime($open_since);
    $current_date = new DateTime();
    $interval = $open_date->diff($current_date);

    // Return the difference in a readable format
    return $interval->days . ' days';
}

function getBadgeColor($difficulty) {
    if (preg_match('/4[abc][+]*/', $difficulty)) {
        return 'bg-success';  // Green for 4a to 4c+
    } elseif (preg_match('/5[abc][+]*/', $difficulty)) {
        return 'bg-primary';  // Blue for 5a to 5c+
    } elseif (preg_match('/6[abc][+]*/', $difficulty)) {
        return 'bg-danger';   // Red for 6a to 6c+
    } elseif (preg_match('/7[abc][+]*/', $difficulty)) {
        return 'bg-dark';     // Black for 7a to 7c+
    } elseif (preg_match('/8[abc][+]*/', $difficulty) || intval($difficulty) >= 8) {
        return 'bg-purple';   // Purple for 8a and above
    } else {
        return 'bg-secondary';  // Default badge color
    }
}
#endregion

#region fetch route info
$route_id = $_GET['id'];
function fetchRouteData($id) {
    $api_base_url = "https://3m.alysia.fr/api/?table=routes&id=$id";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_base_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $headers = [
        'Authorization: '.$_ENV['READ_ONLY_HASHED'],
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch) || $response == null) {
        echo 'Error:' . curl_error($ch);
        return [];
    } else {
        return json_decode($response, true);
    }
}

$route = fetchRouteData($route_id);
#endregion

#region fetch user info (if logged in)
$favorite = false;
$topped = false;

$logged_in = false;
if(isset($_SESSION['user'])) {
    $logged_in = true;
}
#endregion

?>

<style>
        .route-image {
            max-width: 100%;
            height: auto;
        }

        .route-info {
            padding-left: 20px;
        }

        .badge-custom {
            font-size: 1.25rem;
        }

        .btn-secondary {
            transition: background-color 0.3s ease;
        }

         /* Animation for coloring buttons on hover */
        .btn-secondary:hover {
            background-color: #f0ad4e !important; /* Change to yellow for favorite on hover */
            color: white !important;
            cursor: pointer;
        }

        .btn-secondary:hover.btn-success {
            background-color: #5cb85c !important; /* Change to green for topped on hover */
            color: white !important;
            cursor: pointer;
        }
        
    </style>

</head>

<body>

<div class="container mt-5">

    

    <div class="row">
        <!-- Left half: Route image -->
        <div class="col-md-6">
            <img src="<?php echo $route['image_url']; ?>" alt="<?php echo $route['name']; ?>" class="route-image">
        </div>


        

        <!-- Right half: Route information -->
        <div class="col-md-6 route-info">
            

            

            <!-- FAVORITES AND TOPPED BUTTONS -->
            <div class="user-options mt-4">
                <button class="btn btn-secondary" onclick="window.history.back();">
                    <i class="bi bi-arrow-left"></i>
                </button>

                
                <!-- Add/remove from favorites -->
                <button class="btn <?php echo $favorite ? 'btn-warning' : 'btn-secondary'; ?>" 
                    <?php echo $logged_in ? '' : 'disabled'; ?>>
                    <i class="bi bi-star"></i> <?php echo $favorite ? 'Remove from Favorites' : 'Add to Favorites'; ?>
                </button>

                <!-- Mark/unmark as topped -->
                <button class="btn <?php echo $topped ? 'btn-success' : 'btn-secondary'; ?>" 
                    <?php echo $logged_in ? '' : 'disabled'; ?>>
                    <i class="bi bi-check-lg"></i> <?php echo $topped ? 'Unmark as Topped' : 'Mark as Topped'; ?>
                </button>
            </div>

            <h2><?php echo $route['name']; ?></h2>
            
            <!-- Difficulty -->
            <p>
                Difficulty: 
                <span class="badge <?php echo getBadgeColor($route['difficulty']); ?> badge-custom">
                    <?php echo $route['difficulty']; ?>
                </span>
            </p>
            
            <!-- Created Date -->
            <p>Created on: <?php echo date('d M Y', strtotime($route['created_at'])); ?></p>
            
            <!-- Open Since (calculated) -->
            <p>Open since: <?php echo calculateOpenSince($route['created_at']); ?></p>
            
            <!-- Route Setter -->
            <p>Route Setter ID: <?php echo $route['route_setter_id']; ?></p>

            <!-- Color -->
            <p>
                Color: 
                <span class="route-color-box" style="background-color: <?php echo $route['color']; ?>;"></span>
            </p>
            
            <!-- Status -->
            <p>Status: <?php echo $route['status']; ?></p>
            
            <!-- Description section -->
            <h4>Description</h4>
            <p><?php echo $route['description']; ?></p>

            <!-- Connected users' options -->
            
        </div>
    </div>
</div>


<?php include $path.'/views/components/footer.php'; ?>
