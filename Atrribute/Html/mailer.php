<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/html/PHPMailer/src/Exception.php';
require '/html/PHPMailer/src/PHPMailer.php';
require '/html/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp-relay.brevo.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->Username = "7aa4f2001@smtp-brevo.com";
$mail->Password = "NpKfyRESk4vHx03P";

$mail->isHTML(true);

return $mail;