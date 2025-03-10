<?php


$path = $_SERVER['DOCUMENT_ROOT'] . "/";


require_once 'vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable("/var");
$dotenv->load();

$servername = $_ENV['SERVER_NAME'];
$username = $_ENV['DATABASE_USERNAME'];
$password = $_ENV['DATABASE_PASSWORD'];
$dbname = $_ENV['DATABASE_NAME'];


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars(trim($_POST["nom"]));
    $prenom = htmlspecialchars(trim($_POST["prenom"]));
    $licence = htmlspecialchars(trim($_POST["licence"]));

    /**

    $recaptcha_secret = "TA_CLE_SECRETE";
    $recaptcha_response = $_POST["g-recaptcha-response"];

    if (!$recaptcha_response) {
        echo "<p style='color: red;'>Veuillez cocher la case 'Je ne suis pas un robot'.</p>";
        exit();
    }

    $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}";
    $verify_response = file_get_contents($verify_url);
    $verify_data = json_decode($verify_response);

    if (!$verify_data->success) {
        echo "<p style='color: red;'>Échec de la vérification reCAPTCHA.</p>";
        exit();
    }
    **/


    if (!empty($nom) && !empty($prenom) && !empty($licence)) {
        $sql = "SELECT created_at FROM presences WHERE nom=? AND prenom=? ORDER BY created_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nom, $prenom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_submission = strtotime($row["created_at"]);
            if (time() - $last_submission < 60) { // 60 secondes
                echo "<p style='color: red;'>Vous avez déjà émargé récemment.</p>";
                exit();
            }
        }


        $stmt = $conn->prepare("INSERT INTO presences (nom, prenom, licence) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nom, $prenom, $licence);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Inscription enregistrée avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Erreur lors de l'enregistrement.</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color: red;'>Tous les champs sont requis.</p>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Émargement</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <h2>Inscription à la séance</h2>
    <form action="emargement.php" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="licence">Numéro de licence :</label>
        <input type="text" id="licence" name="licence" required><br>

        <!--<div class="g-recaptcha" data-sitekey="TA_CLE_SITE"></div>-->


        <button type="submit">S'enregistrer</button>
    </form>
</body>

</html>