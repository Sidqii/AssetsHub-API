<?php

header("Content-Type: application/json");
require "../connection/connect_db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "invalid request method",
    ]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"] ?? "";
$password = $data["password"] ?? "";
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Emali dan Password wajib diisi",
    ]);
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode([
        "status" => "error",
        "message" => "email sudah terdaftar",
    ]);
    exit();
}

$stmt->close();

$defaultRoleId = 2;

$stmt = $conn->prepare("INSERT INTO users (email, password, role_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $email, $hashedPassword, $defaultRoleId);
if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => "Registrasi berhasil",
    ]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal registrasi"]);
}

$stmt->close();

$conn->close();
