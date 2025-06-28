<?php
session_start();
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'vendor/autoload.php';
require_once 'send_mail.php';

// Check if logged in
if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$type = $data['type'] ?? '';

if (!$id || !in_array($type, ['package', 'guide'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

// DB connection
$conn = new mysqli('sql12.freesqldatabase.com', 'sql12786577', 'L4hlZ3zHSD', 'sql12786577');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB Error']);
    exit;
}

$table = $type === 'package' ? 'package_bookings' : 'guide_bookings';
$userId = $_SESSION['userid'];

// ✅ Step 1: Fetch details before deletion
$fetchStmt = $conn->prepare("SELECT name, email FROM $table WHERE id = ? AND user_id = ?");
$fetchStmt->bind_param('ii', $id, $userId);
$fetchStmt->execute();
$fetchResult = $fetchStmt->get_result();

if ($fetchResult->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Booking not found']);
    exit;
}

$booking = $fetchResult->fetch_assoc();
$name = $booking['name'];
$email = $booking['email'];
$label = $type === 'package' ? 'Tour Package' : 'Guide';

// ✅ Step 2: Delete
$stmt = $conn->prepare("DELETE FROM $table WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $id, $userId);
$stmt->execute();
$success = $stmt->affected_rows > 0;

// ✅ Step 3: Send cancellation email
if ($success) {
    $subject = "$label Booking Cancelled";
    $body = "
        <h3>Hello $name,</h3>
        <p>Your <strong>$label</strong> booking has been successfully cancelled.</p>
        <p>If this was a mistake, feel free to book again anytime at <strong>TravelNow</strong>.</p>
    ";
    sendBookingEmail($email, $subject, $body);
}

echo json_encode(['success' => $success]);
$stmt->close();
$conn->close();
?>
