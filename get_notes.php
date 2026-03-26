<?php
$conn = new mysqli("localhost", "root", "", "walletnotes");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_GET['user_id'];

$sql = "SELECT id_notes, title, amount, sources, created_at 
        FROM notes 
        WHERE user_id = '$user_id'
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);

$conn->close();
?>
