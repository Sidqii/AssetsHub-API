<?php
function register($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'] ?? '';
    $pass = $data['password'] ?? '';
    $defaultRole = 2;

    $hashPassword = password_hash($pass, PASSWORD_BCRYPT);
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        http_response_code(409);
        return;
    }

    $stmt = $conn->prepare('INSERT INTO users (email, password, role_id) VALUES (?, ?, ?)');
    $stmt->bind_param('ssi', $email, $hashPassword, $defaultRole);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['status' => 'succes', 'message' => 'Registrasi berhasil']);
        return;
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal registrasi']);
    }
}