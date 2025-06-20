<?php
function ambilDataUser($conn)
{
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT id, username, email, role_id FROM users WHERE id = ?');

    if (!$stmt) {
        http_response_code(500);
        return;
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        http_response_code(404);
    }
    $stmt->close();
}