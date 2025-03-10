<!DOCTYPE html>
<html lang="en">


<head>
    <?php


    include $path . '/views/components/autoload.php';

    #region session and header
    if (isset($_SESSION['user'])) {
        include $path . '/views/components/header-online.php';
    } else {
        include $path . '/views/components/header-offline.php';
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

    $data = [
        'id' => $route_id
    ];
    $route = getData('routes', $data);
    #endregion
    
    #region fetch user info (if logged in)
    $favorite = false;
    $status = "Uncompleted";

    $logged_in = false;
    if (isset($_SESSION['user'])) {
        $idUser = $_SESSION['user'];
        $logged_in = true;

        $datauser = [
            'user_id' => $idUser,
            'route_id' => $route_id
        ];
        $route_status = getData('route_status', $datauser);

        $favorite = $route_status['favorite'] ?? false; // Default to false if not set
        $status = $route_status['status'] ?? 'null';   // Default to 'null' if not set
    
        $favorite = $route_status['favorite'];
        $status = $route_status['status'];

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

        .btn-secondary:hover {
            background-color: #f0ad4e !important;
            color: white !important;
            cursor: pointer;
        }

        .btn-secondary:hover.btn-success {
            background-color: #5cb85c !important;
            color: white !important;
            cursor: pointer;
        }

        .btn {
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
        }

        .btn:active {
            transform: scale(0.95);
            /* Slight "press" effect */
        }
    </style>


</head>

<body>

    <div class="container mt-5">

        <div class="row">
            <div class="col-md-6">
                <img src="<?= $route['image_url']; ?>" alt="<?= $route['name']; ?>" class="route-image">
            </div>

            <div class="col-md-6 route-info">

                <div class="user-options mt-4">
                    <button class="btn btn-secondary" onclick="window.history.back();">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <button
                        class="btn <?php echo ($status === 'completed') ? 'btn-success' : ($status === 'project' ? 'btn-primary' : 'btn-secondary'); ?>"
                        <?php echo $logged_in ? '' : 'disabled'; ?> id="status-btn" data-route-id="<?= $route['id']; ?>"
                        data-status="<?= $status; ?>" data-bs-toggle="tooltip" title="<?php
                          echo ($status === 'completed') ? 'Reset to uncompleted' :
                              ($status === 'project' ? 'Mark as completed' : 'Mark as project');
                          ?>">
                        <i
                            class="bi <?php echo ($status === 'completed') ? 'bi-check-circle-fill' : ($status === 'project' ? 'bi-circle-half' : 'bi-circle'); ?>"></i>
                        <?php
                        if ($status === 'null') {
                            echo 'Uncompleted';
                        } elseif ($status === 'project') {
                            echo 'Project';
                        } elseif ($status === 'completed') {
                            echo 'Completed';
                        }
                        ?>
                    </button>


                    <button class="btn <?php echo $favorite ? 'btn-warning' : 'btn-secondary'; ?>" <?php echo $logged_in ? '' : 'disabled'; ?> id="favorite-btn" data-route-id="<?= $route['id']; ?>"
                        data-favorite="<?= $favorite ? 1 : 0; ?>" data-bs-toggle="tooltip"
                        title="<?= $favorite ? 'Click to remove from favorites' : 'Click to add to favorites'; ?>">
                        <i class="bi <?php echo $favorite ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                        <?php echo $favorite ? 'Remove from Favorites' : 'Add to Favorites'; ?>
                    </button>


                </div>

                <h2><?= $route['name']; ?></h2>

                <p>
                    Difficulty:
                    <span class="badge <?= getBadgeColor($route['difficulty']); ?> badge-custom">
                        <?= $route['difficulty']; ?>
                    </span>
                </p>

                <p>Created on: <?= date('d M Y', strtotime($route['created_at'])); ?></p>

                <p>Open since: <?= calculateOpenSince($route['created_at']); ?></p>

                <p>Route Setter ID: <?= $route['route_setter_id']; ?></p>

                <p>
                    Color:
                    <span class="route-color-box" style="background-color: <?= $route['color']; ?>;"></span>
                </p>

                <p>Status: <?= $route['status']; ?></p>

                <h4>Description</h4>
                <p><?= $route['description']; ?></p>


            </div>
        </div>
    </div>


    <script src="/vendor/components/jquery/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#favorite-btn').click(function () {
                const routeId = $(this).data('route-id');
                const isFavorite = $(this).data('favorite');

                $.ajax({
                    url: '/functions/toggle_status.php',
                    method: 'POST',
                    data: {
                        method: 'toggle_favorite',
                        route_id: routeId,
                        user_id: <?= $idUser; ?>,
                        action: isFavorite ? 'unfavorite' : 'favorite'
                    },
                    beforeSend: function () {
                        $('#favorite-btn').prop('disabled', true)
                            .prop('disabled', true)
                            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
                    },
                    success: function (response) {
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }

                        if (response.message === "Route_status updated successfully") {
                            const newState = !isFavorite;

                            // Update button text, icon, and color
                            $('#favorite-btn')
                                .prop('disabled', false)
                                .data('favorite', newState)
                                .removeClass('btn-warning btn-secondary')
                                .addClass(newState ? 'btn-warning' : 'btn-secondary')
                                .html(
                                    `<i class="bi ${newState ? 'bi-star-fill' : 'bi-star'}"></i> ` +
                                    `${newState ? 'Remove from Favorites' : 'Add to Favorites'}`
                                );
                        } else {
                            alert('Failed to update favorite status. Please try again.');
                        }
                    },

                    error: function () {
                        alert('An error occurred while processing your request.');
                    }
                });
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#status-btn').click(function () {
                const routeId = $(this).data('route-id');
                const currentStatus = $(this).data('status'); // The current status (null, project, or completed)


                // Determine the next status based on the current status
                let nextAction;
                let nextText;

                if (currentStatus === "null") {
                    nextAction = "project";
                    nextText = "Project";
                } else if (currentStatus === "project") {
                    nextAction = "completed";
                    nextText = "Completed";
                } else if (currentStatus === "completed") {
                    nextAction = "null";
                    nextText = "Uncompleted";
                } else {
                    alert("Invalid status!");
                    return;
                }

                // Send the AJAX request
                $.ajax({
                    url: '/functions/toggle_status.php',
                    method: 'POST',
                    data: {
                        method: 'toggle_status',
                        route_id: routeId,
                        user_id: <?= $idUser; ?>,
                        action: nextAction
                    },
                    beforeSend: function () {
                        $('#status-btn').prop('disabled', true)
                            .prop('disabled', true)
                            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
                    },
                    success: function (response) {
                        if (typeof response === 'string') {
                            response = JSON.parse(response);
                        }

                        if (response.message === "Route_status updated successfully") {
                            $('#status-btn')
                                .prop('disabled', false)
                                .data('status', nextAction)
                                .removeClass('btn-success btn-primary btn-secondary')
                                .addClass(
                                    nextAction === 'completed' ? 'btn-success' :
                                        nextAction === 'project' ? 'btn-primary' :
                                            'btn-secondary'
                                )
                                .html(
                                    `<i class="bi ${nextAction === 'completed' ? 'bi-check-circle-fill' :
                                        nextAction === 'project' ? 'bi-circle-half' :
                                            'bi-circle'
                                    }"></i> ` +
                                    `${nextText}`
                                );
                        } else {
                            alert('Failed to update status. Please try again.');
                        }
                    },

                    error: function () {
                        alert('An error occurred while processing your request.');
                    }
                });
            });
        });
    </script>



    <?php include $path . '/views/components/footer.php'; ?>