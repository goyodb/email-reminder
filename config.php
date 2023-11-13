<?php
// Configuración de la base de datos
$db = new PDO('mysql:host=localhost;dbname=email_reminder;charset=utf8', 'root', 'root');

// Cconfiguracion de Composer vendor
require 'vendor/autoload.php';

// Configuración de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Configuración SMTP
$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
$mail->Host = 'SMTP.SERVER';
$mail->SMTPAuth = true;
$mail->Username = 'user@domain.com';
$mail->Password = 'PASSWORD';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
?>