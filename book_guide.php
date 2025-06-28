<?php
session_start();

require 'send_mail.php';

// ✅ Check login
if (!isset($_SESSION['userid'])) {
    die("You must be logged in to book a guide.");
}

// DB connection
$conn = new mysqli("sql12.freesqldatabase.com", "sql12786577", "L4hlZ3zHSD", "sql12786577");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle booking
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id        = $_SESSION['userid'];
    $name           = $_POST['name'];
    $email          = $_POST['email'];
    $guide_name     = $_POST['guide_name'];
    $booking_date   = $_POST['booking_date'];
    $people_count   = $_POST['people_count'];

    // ✅ Now include user_id in the DB insert
    $stmt = $conn->prepare("INSERT INTO guide_bookings (user_id, name, email, guide_name, booking_date, people_count)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $user_id, $name, $email, $guide_name, $booking_date, $people_count);

    if ($stmt->execute()) {
        header("Location: index.php?guide=booked");
        exit();
    } else {
        echo "❌ Guide booking failed: " . $stmt->error;
    }

    $stmt->close();
}

$subject = "Guide Booking Confirmed!";
$body = "
    <h3>Hello $name,</h3>
    <p>Your booking for guide <strong>$guide_name</strong> on <strong>$booking_date</strong> for <strong>$people_count</strong> people has been confirmed.</p>
    <p>We look forward to your amazing journey with <strong>TravelNow</strong>!</p>
";

sendBookingEmail($email, $subject, $body);
$conn->close();
?>
