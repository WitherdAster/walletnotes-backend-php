<?php
$conn = new mysqli("localhost", "root", "", "walletnotes");

$id = $_POST['id_notes'];
$title = $_POST['title'];
$amount = $_POST['amount'];
$sources = $_POST['sources'];

$sql = "UPDATE notes 
        SET title='$title', amount='$amount', sources='$sources' 
        WHERE id_notes='$id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil diupdate"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
}
?>
