<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'travelnow';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT place, travelers_count FROM travel_leaderboard ORDER BY travelers_count DESC";
$result = $conn->query($sql);

$leaderboard = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
}

echo json_encode($leaderboard);

$conn->close();
?>
