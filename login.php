<?php
session_start();

// Database credentials
$host = 'localhost';
$dbname = 'travelnow';
$username = 'root';
$password = '';

// Create DB connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['userid'] ?? '');
    $passwordInput = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($passwordInput)) {
        echo "⚠️ Please fill in both email and password.";
        exit;
    }

    // Fetch user by email
    $stmt = $conn->prepare("SELECT id, name, email, phoneNo, location, bio, password, profile_photo, cover_photo FROM users WHERE email = ?");
    if (!$stmt) {
        die("❌ Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Validate user and password
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($passwordInput, $user['password'])) {
            // Set session values
            $_SESSION['userid']         = $user['id'];
            $_SESSION['email']          = $user['email'];
            $_SESSION['name']           = $user['name'];
            $_SESSION['phoneNo']        = $user['phoneNo'];
            $_SESSION['location']       = $user['location'];
            $_SESSION['bio']            = $user['bio'];
            $_SESSION['profile_photo']  = $user['profile_photo'];
            $_SESSION['cover_photo']    = $user['cover_photo'];

            // Redirect
            header("Location: index.php");
            exit();
        } else {
            echo "❌ Incorrect password.";
        }
    } else {
        echo "❌ No user found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>
