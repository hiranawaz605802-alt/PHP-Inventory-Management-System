<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// 🔥 REUSABLE MAIL FUNCTION
function sendMail($to, $subject, $message){

    $mail = new PHPMailer(true);

    try {

        // SMTP SETTINGS
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // 🔑 YOUR GMAIL DETAILS
        $mail->Username = 'sadaf.ayazchohan808@gmail.com';
        $mail->Password = 'your-app-password'; // Gmail App Password

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // EMAIL SETUP
        $mail->setFrom('sadaf.ayazchohan808@gmail.com', 'PHP System');
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }
}
?>