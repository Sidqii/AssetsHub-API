<?php

header('Content-Type: application/json');
require('../connection/connect_db.php');
require('../../method/register/post.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        register($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tiidak diizinkan']);
        break;
}