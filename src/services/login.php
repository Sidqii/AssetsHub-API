<?php

header("Content-Type: application/json");
include "../connection/connect_db.php";
$json = file_get_contents("php://input");
$data = json_decode($json, true);
if (!$data) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit();
}

$email = strtolower(trim($data["email"] ?? ""));
$password = $data["password"] ?? "";
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Email dan password harus diisi",
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Format email tidak valid",
    ]);
    exit();
}

$sql = "SELECT id, email, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Kesalahan server: " . $conn->error,
    ]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$response = [];
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user["password"])) {
        http_response_code(200);
        $response = [
            "status" => "success",
            "message" => "Login berhasil",
            "user" => [
                "id" => $user["id"],
                "email" => $user["email"],
            ],
        ];
    } else {
        http_response_code(401);
        $response = ["status" => "error", "message" => "Password salah"];
    }
} else {
    http_response_code(404);
    $response = ["status" => "error", "message" => "Email tidak ditemukan"];
}

$stmt->close();
$conn->close();
echo json_encode($response);
exit();
