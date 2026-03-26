<?php
header('Content-Type: application/json');

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "walletnotes");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal"]);
    exit();
}

// Ambil data dari POST
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi input
if (empty($name) || empty($phone) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Field tidak boleh kosong"]);
    exit();
}

// 1. Cek apakah nomor HP sudah digunakan
$checkSql = "SELECT phone FROM users WHERE phone = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Nomor HP sudah terdaftar"]);
} else {
    // 2. 🔥 HASH PASSWORD DENGAN BCRYPT
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 3. Simpan ke Database
    $insertSql = "INSERT INTO users (name, phone, password, created_at) VALUES (?, ?, ?, NOW())";
    $stmtInsert = $conn->prepare($insertSql);
    $stmtInsert->bind_param("sss", $name, $phone, $hashed_password);

    if ($stmtInsert->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Register berhasil",
            "user_id" => (string)$conn->insert_id, // Mengirim ID agar bisa langsung login
            "name" => $name
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menyimpan data: " . $conn->error
        ]);
    }
    $stmtInsert->close();
}

$stmt->close();
$conn->close();
?>