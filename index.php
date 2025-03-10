<?php


error_reporting(E_ALL); // Report all errors
ini_set('display_errors', '1'); // Don't display errors to the user
ini_set('log_errors', '1'); // Log errors to a file

// Set the timezone
date_default_timezone_set('Europe/Paris');


session_start();

$path = $_SERVER['DOCUMENT_ROOT'];
require_once 'vendor/autoload.php';
include 'functions/http_functions.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


#region determine if mobile
function isMobile() {
    return preg_match('/(android|iphone|ipad|mobile)/i', $_SERVER['HTTP_USER_AGENT']);
}

#endregion

#region determine page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$validPages = [
    'route', 'routes',
    'sector', 'sectors',
    'wall', 'walls',
    'user', 'users',
    'login'
];

if (!in_array($page, $validPages)) {
    $page = "home";
}

if (isMobile()) {
    $extension = "-mobile.php";
} else {
    $extension = ".php";
}
#endregion


try {

    if (isset($_SESSION['user'])) {
        #region logout
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'logout') {
            session_destroy();
            $page = "home";
            header("Location: /index.php");
            exit();
        }
        #endregion

    } else {
        #region login
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method']) && $_POST['method'] === 'login') {

            $captcha = false;

            // Google captcha
            $secretKey = $_ENV['CAPTCHA_SECRET'];

            // Verify reCAPTCHA response
            if (isset($_POST['g-recaptcha-response'])) {
                $recaptchaResponse = $_POST['g-recaptcha-response'];
                $remoteIp = $_SERVER['REMOTE_ADDR'];

                // API request to Google
                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $data = [
                    'secret' => $secretKey,
                    'response' => $recaptchaResponse,
                    'remoteip' => $remoteIp
                ];

                // Use cURL to send the request
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                // Decode JSON response from Google
                $responseKeys = json_decode($response, true);

                // Check if verification was successful
                if ($responseKeys['success']) {
                    // CAPTCHA passed - proceed with registration
                    $captcha = true;
                    // Insert the user data into the database here
                } else {
                    // CAPTCHA failed
                    $error = "CAPTCHA verification failed. Please try again.";
                    $page = "login";
                }
            } else {
                $error = "CAPTCHA response is missing. Please complete the CAPTCHA.";
            }



            if ($captcha) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $api_url = "http://les3mousquetons.fr/api/?table=users&email=" . urlencode($email);


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: ' . $_ENV['READ_ONLY_HASHED']
                ]);

                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    $error = "Une erreur est survenue lors de la connexion au serveur";
                    $page = "login";
                } else {
                    $userData = json_decode($response, true);

                    if (!empty($userData)) {
                        $hashed_password = $userData[0]['password_hashed'];

                        if (password_verify($password, $hashed_password)) {
                            $_SESSION['user'] = $userData[0]['id'];
                            $_SESSION['username'] = $userData[0]['username'];
                            $_SESSION['email'] = $userData[0]['email'];
                            $_SESSION['role'] = $userData[0]['role'];

                            $page = "home";
                        } else {
                            $page = "login";
                            $error = "Utilisateur ou mot de passe incorrect";
                        }
                    } else {
                        $page = "login";
                        $error = "Username or password wrong";
                    }
                }
                curl_close($ch);
            }

        }
        #endregion
    }

} catch (Exception $e) {

    // TODO remove this line in production
    $GeneralError = $e->getMessage();
    echo $GeneralError;
    $page = "home";
}


#region generate page
try {
    include($path . "/views/" . $page . $extension);

    // avoid showing the error message to the user
} catch (Exception $e) {

    // TODO remove this line in production
    $PageError = $e->getMessage();
    echo $PageError;
    $page = "home";
    include($path . "/views/" . $page . $extension);
}
#endregion