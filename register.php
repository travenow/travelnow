<?php
session_start(); // Start session to auto-login after registration
// Create a database connection
$conn = new mysqli("sql12.freesqldatabase.com", "sql12786577", "L4hlZ3zHSD", "sql12786577");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and collect form data
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phoneNo  = trim($_POST['phoneNo']);
    $location = trim($_POST['location'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Prepare the SQL statement
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, phoneNo, password, location, bio)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssss", $name, $email, $phoneNo, $password, $location, $bio);

    // Execute statement
    if ($stmt->execute()) {
        // Auto-login by setting session variables
        $_SESSION['userid']   = $email;
        $_SESSION['name']     = $name;
        $_SESSION['email']    = $email;
        $_SESSION['phoneNo']  = $phoneNo;
        $_SESSION['location'] = $location;
        $_SESSION['bio']      = $bio;

        // Redirect to profile page
        header("Location: login.php");
        exit();
    } else {
        // Handle duplicate email/phone
        if (strpos($stmt->error, "Duplicate entry") !== false) {
            echo "⚠️ Error: Email or phone number already exists.";
        } else {
            echo "❌ Error: " . $stmt->error;
        }
    }

    // Clean up
    $stmt->close();
}
$conn->close();
?>
