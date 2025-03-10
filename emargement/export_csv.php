<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "3m";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

$date_filter = $_POST["date"];

$sql = "SELECT nom, prenom, licence, date_presence FROM presences WHERE DATE(date_presence) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date_filter);
$stmt->execute();
$result = $stmt->get_result();

// Ouvrir un fichier CSV en écriture
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="presences.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Nom', 'Prénom', 'Licence', 'Date']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);

$stmt->close();
$conn->close();
?>