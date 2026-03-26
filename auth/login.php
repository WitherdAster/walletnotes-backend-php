<?php
header('Content-Type: application/json');

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "walletnotes");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Gagal koneksi database"]);
    exit();
}

$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($phone) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Phone dan password wajib diisi"]);
    exit();
}

// 1. Cari user berdasarkan nomor telepon saja
$stmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // 2. 🔥 VERIFIKASI BCRYPT DISINI
    if (password_verify($password, $row['password'])) {
        echo json_encode([
            "status" => "success",
            "user_id" => (string)$row['user_id'], // Pastikan user_id berupa string sesuai model Kotlin
            "name" => $row['name'],
            "message" => "Login Berhasil"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Password salah"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Nomor HP tidak terdaftar"
    ]);
}

$stmt->close();
$conn->close();
?>