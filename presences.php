<?php

$path = $_SERVER['DOCUMENT_ROOT'] . "/";


require_once 'vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['SERVER_NAME'];
$username = $_ENV['DATABASE_USERNAME'];
$password = $_ENV['DATABASE_PASSWORD'];
$dbname = $_ENV['DATABASE_NAME'];


$admin_password = $_ENV['ADMIN_PASSWORD'];
session_start();

if (!isset($_SESSION["logged_in"])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["password"] == $admin_password) {
        $_SESSION["logged_in"] = true;
    } else {
        echo '<form method="POST">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password">
                <button type="submit">Connexion</button>
              </form>';
        exit();
    }
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

$date_filter = isset($_GET["date"]) ? $_GET["date"] : date("Y-m-d");

$sql = "SELECT nom, prenom, licence, date_presence FROM presences WHERE DATE(date_presence) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date_filter);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présences</title>
</head>

<body>
    <h2>Liste des présences</h2>

    <form method="GET" action="presences.php">
        <label for="date">Choisir une date :</label>
        <input type="date" id="date" name="date" value="<?= $date_filter ?>">
        <button type="submit">Filtrer</button>
    </form>

    <form method="POST" action="export_csv.php">
        <input type="hidden" name="date" value="<?= $date_filter ?>">
        <button type="submit">Exporter en CSV</button>
    </form>

    <form method="POST" action="export_pdf.php">
        <input type="hidden" name="date" value="<?= $date_filter ?>">
        <br>
        <button disabled type="submit">Exporter en PDF</button>

    </form>
    <br>

    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Licence</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["nom"]) ?></td>
                <td><?= htmlspecialchars($row["prenom"]) ?></td>
                <td><?= htmlspecialchars($row["licence"]) ?></td>
                <td><?= $row["date_presence"] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>