<?php
session_start();
require 'send_mail.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['userid'])) {
    die("You must be logged in to book a package.");
}

// DB connection
$conn = new mysqli("sql12.freesqldatabase.com", "sql12786577", "L4hlZ3zHSD", "sql12786577");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id    = $_SESSION['userid'];
    $name       = $_POST['name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $people     = (int)$_POST['people'];
    $package    = $_POST['package'] ?? '';

    // Validate input
    if ($name && $email && $start_date && $people > 0 && $package) {
        // Insert booking
        $stmt = $conn->prepare("INSERT INTO package_bookings (user_id, name, email, start_date, people, package) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $user_id, $name, $email, $start_date, $people, $package);

        if ($stmt->execute()) {
            // ✅ Send email BEFORE redirect
            $subject = "🎉 Package Booking Confirmed!";
            $body = "
                <h3>Dear $name,</h3>
                <p>Your tour package <strong>$package</strong> starting on <strong>$start_date</strong> for <strong>$people</strong> people has been successfully booked.</p>
                <p>Thank you for choosing <strong>TravelNow</strong>!</p>
            ";
            sendBookingEmail($email, $subject, $body);

            // ✅ Redirect after mail is sent
            header("Location: index.php?booked=success");
            exit();
        } else {
            echo "❌ Booking failed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "⚠️ Please fill in all fields correctly.";
    }
}

$conn->close();
?>
