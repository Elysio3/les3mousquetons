<?php
require_once('tcpdf/tcpdf.php'); // Inclure la bibliothèque TCPDF

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

// Création du PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, "Liste des présences du " . $date_filter, 0, 1, 'C');

// En-têtes de colonne
$pdf->Cell(40, 10, 'Nom', 1);
$pdf->Cell(40, 10, 'Prénom', 1);
$pdf->Cell(40, 10, 'Licence', 1);
$pdf->Cell(60, 10, 'Date', 1);
$pdf->Ln();

// Contenu des lignes
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(40, 10, $row['nom'], 1);
    $pdf->Cell(40, 10, $row['prenom'], 1);
    $pdf->Cell(40, 10, $row['licence'], 1);
    $pdf->Cell(60, 10, $row['date_presence'], 1);
    $pdf->Ln();
}

$pdf->Output('presences.pdf', 'D'); // Téléchargement du PDF

$stmt->close();
$conn->close();