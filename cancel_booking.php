<?php
session_start();
header('Content-Type: application/json');

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

$conn = new mysqli('sql12.freesqldatabase.com', 'sql12786577', 'L4hlZ3zHSD', 'sql12786577');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB Error']);
    exit;
}

$table = $type === 'package' ? 'package_bookings' : 'guide_bookings';
$stmt = $conn->prepare("DELETE FROM $table WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $id, $_SESSION['userid']);
$stmt->execute();

echo json_encode(['success' => $stmt->affected_rows > 0]);
$stmt->close();
$conn->close();
