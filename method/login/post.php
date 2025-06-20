<?php

function login($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    $email = strtolower(trim($data['email']));
    $pass = $data['password'];

    $stmt = $conn->prepare('SELECT id, password FROM users WHERE email = ?');
    if (!$stmt) {
        http_response_code(500);
        return;
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($pass, $user['password'])) {
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil',
                'user_id' => $user['id']
            ]);
        } else {
            http_response_code(401);
        }
    } else {
        http_response_code(404);
    }
}