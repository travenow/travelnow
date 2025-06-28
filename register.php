<?php
session_start(); // Start session to auto-login after registration

// Database connection
$conn = new mysqli("sql12.freesqldatabase.com", "sql12786577", "L4hlZ3zHSD", "sql12786577");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phoneNo  = trim($_POST['phoneNo']);
    $location = trim($_POST['location'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("
        INSERT INTO users (name, email, phoneNo, password, location, bio)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $name, $email, $phoneNo, $password, $location, $bio);

    if ($stmt->execute()) {
        // ✅ Get the inserted user ID
        $_SESSION['userid']   = $conn->insert_id;
        $_SESSION['name']     = $name;
        $_SESSION['email']    = $email;
        $_SESSION['phoneNo']  = $phoneNo;
        $_SESSION['location'] = $location;
        $_SESSION['bio']      = $bio;

        // Redirect to index after auto-login
        header("Location: index.php");
        exit();
    } else {
        if (strpos($stmt->error, "Duplicate entry") !== false) {
            echo "⚠️ Error: Email or phone number already exists.";
        } else {
            echo "❌ Error: " . $stmt->error;
        }
    }

    $stmt->close();
}
$conn->close();
?>
