<?php
session_start();



$path = $_SERVER['DOCUMENT_ROOT'];
require_once 'vendor/autoload.php';

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable("/var");
$dotenv->load();


// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // Create a new PHPMailer instance with exceptions enabled

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = 'ssl'; // Use SSL
    $mail->Port = $_ENV['SMTP_PORT'];

    // Email settings
    $mail->setFrom($_ENV['SMTP_USER'], '3M Notifications'); // Sender's email and name
    $mail->addAddress('mael.kerviche@outlook.com'); // Recipient's email

    $mail->Subject = 'Test Email from 3M Project';
    $mail->Body    = 'Hello, this is a test email from your 3M project mailing system.';

    // Send email
    if($mail->send()) {
        echo 'Email sent successfully';
    } else {
        echo 'Email could not be sent.';
    }
} catch (Exception $e) {
    echo "Error in sending email: {$mail->ErrorInfo}";
}


?>






helo test