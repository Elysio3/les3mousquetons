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
    
    ?>
    <style>
        /* Default styles for larger screens (desktop view) */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            max-height: 350px;
            /* Tall card for desktop */
            border-radius: 15px;
            position: relative;
            /* Allow title and text to be positioned over the image */
            overflow: hidden;
            /* Ensure content does not overflow outside the card */
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: auto;
            height: auto;
        }

        .card-body {
            position: absolute;
            bottom: 0;
            /* Position at the bottom of the card */
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            /* Semi-transparent dark background */
            color: white;
            /* Make the text white for contrast */
            padding: 10px;
            transition: background-color 0.3s ease;
            /* Smooth transition for hover effect */
        }

        .card:hover .card-body {
            background-color: rgba(0, 0, 0, 0.8);
            /* Darker background on hover */
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }

        .card-text {
            font-size: 1.2rem;
            margin: 0;
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
          <div class="col-md-6">
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