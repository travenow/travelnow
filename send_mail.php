<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // Composer autoload

function sendBookingEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'travelnowbyteamx@gmail.com'; // Your Gmail
        $mail->Password   = 'odxhdkixxrvjkztj';          // Your Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email sender/recipient
        $mail->setFrom('travelnowbyteamx@gmail.com', 'TravelNow');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Email error: " . $mail->ErrorInfo;
    }
}
?>
