<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$send_geo = isset($_POST['send_geo']);
$geo = $_POST['geo'];

var_dump($_FILES);
echo "<br>";
echo "<br>";

var_dump($_POST);
echo "<br>";
echo "<br>";

if ((!$email) || (!$subject) || (!$message)) die();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';
require_once(__DIR__ . '/../config.php');

// Passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
  // Server settings
  $mail->SMTPDebug = SMTP::DEBUG_SERVER;
  $mail->isSMTP();
  $mail->Host = getenv('SMTP_HOST');
  $mail->SMTPAuth = true;
  $mail->Username = getenv('SMTP_USER');
  $mail->Password = getenv('SMTP_PASS');
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port = getenv('SMTP_PORT');

  // Recipients
  $mail->setFrom(getenv('SMTP_FROM'), '');
  $mail->addAddress(getenv('SMTP_TO'), '');
  // $mail->addReplyTo($email, $name);
  $mail->addBCC($email, $name);

  //Attachments
  if ((isset($_FILES['photo'])) && ($_FILES['photo']['error'] == UPLOAD_ERR_OK)) {
    $mail->addAttachment($_FILES['photo']['tmp_name'], $_FILES['photo']['name']);
  }

  // Body
  $body = $message;
  if (($send_geo) && ($geo)) $body .= "\n\nUbicación del problema: https://www.google.com/maps/search/?api=1&query=$geo\n";
  $body .= "\n\nDatos de contacto:\n";
  $body .= "Nombre: $name\n";
  $body .= "Correo electrónico: $email\n";
  if ($phone) $body .= "Teléfono: $phone\n";
  if ($address) $body .= "Dirección: $address\n";

  // Content
  $mail->isHTML(false);
  $mail->Subject = $subject;
  $mail->Body = $body;
  // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

  $mail->send();
  echo 'Message has been sent';
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
