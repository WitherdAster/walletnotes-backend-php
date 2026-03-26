<?php
$conn = new mysqli("localhost", "root", "", "walletnotes");

$id = $_POST['id_notes'];

$sql = "DELETE FROM notes WHERE id_notes='$id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil dihapus"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
}
?>
