<?php
$conn = new mysqli("localhost", "root", "", "walletnotes");

$user_id = $_GET['user_id'];
$filter = $_GET['filter']; // 1D / 1W / 1M

$where = "";

if ($filter == "1D") {
    $where = "DATE(created_at) = CURDATE()";
} elseif ($filter == "1W") {
    $where = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filter == "1M") {
    $where = "created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
}

$sql = "SELECT id_notes, title, amount, sources, created_at 
        FROM notes 
        WHERE user_id = '$user_id' AND $where
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);

$conn->close();
?>
