<?php
$conn = new mysqli("localhost", "root", "", "walletnotes");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];
$title = $_POST['title'];
$amount = $_POST['amount'];
$sources = $_POST['sources'];

$sql = "INSERT INTO notes (user_id, title, amount, sources, created_at)
        VALUES ('$user_id', '$title', '$amount', '$sources', NOW())";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil ditambahkan"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
}

$conn->close();
?>
