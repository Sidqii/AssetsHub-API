<?php
header('Content-Type: application/json');
require('../connection/connect_db.php');
require('../../method/user/get.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        ambilDataUser($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tidak diizinkkan']);
        break;
}