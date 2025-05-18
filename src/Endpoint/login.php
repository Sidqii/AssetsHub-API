<?php

header('Content-Type: application/json');
require('../connection/connect_db.php');
require('../../method/login/post.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        login($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tidak diizinkan']);
        break;
}