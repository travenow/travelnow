<?php
session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['userid'])) {
    die("You must be logged in to book a package.");
}

// Database connection
$conn = new mysqli("localhost", "root", "", "travelnow");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id    = $_SESSION['userid'];
    $name       = $_POST['name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $people     = $_POST['people'] ?? 0;
    $package    = $_POST['package'] ?? '';

    // Basic validation
    if ($name && $email && $start_date && $people > 0 && $package) {
        // Insert into database with user_id
        $stmt = $conn->prepare("INSERT INTO package_bookings (user_id, name, email, start_date, people, package) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $user_id, $name, $email, $start_date, $people, $package);

        if ($stmt->execute()) {
            echo '<div class="alert success">✅ Package booked successfully!</div>';
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
