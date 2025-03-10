<!DOCTYPE html>
<html lang="en">


<head>
    <?php

    include $path . '/views/components/autoload.php';

    #region session and header
    if (isset($_SESSION['user'])) {
        include $path . '/views/components/header-online-mobile.php';
    } else {
        include $path . '/views/components/header-offline-mobile.php';
    }
    #endregion
    
    ?>
    <style>
        .card {
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            max-height: 350px;
            border-radius: 15px;
            position: relative;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }

        .card-text {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .card {
                overflow: hidden;
                max-height: 200px;
                display: block;
            }

            .card img {
                width: auto;
                height: auto;
            }

            .card-body {
                padding: 5px;
            }

            .card-title {
                font-size: 1.2rem;
            }

            .card-text {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .col-md-4 {
                width: 50%;
            }
        }
    </style>


</head>


<div class="container my-4">
    <div class="row">
        <?php

        $walls = getData('walls');

        #region insert walls
        foreach ($walls as $wall) {
            echo '
          <div class="col-md-12">
              <a href="/?page=wall&id=' . $wall["id"] . '" class="text-decoration-none"> 
                  <div class="card mb-4 shadow-sm">
                      
                      <div class="card-body">
                          <h5 class="card-title">' . $wall["name"] . '</h5>
                          <p class="card-text">' . $wall["location"] . '</p>
                      </div>
                      <img src="' . $wall["image_url"] . '" alt="' . $wall["name"] . '" class="img-fluid">
                  </div>
              </a>
          </div>
        ';
        }

        #endregion
        
        ?>


    </div>
</div>


<?php include $path . '/views/components/footer.php'; ?>