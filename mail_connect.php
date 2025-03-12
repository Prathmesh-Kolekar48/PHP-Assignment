<?php

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

// SMTP Configuration
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'kolekarp04082003@gmail.com'; // Your email
$mail->Password = 'vbtc tvyc rdpn xnzl'; // Use App Password if needed
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

?>