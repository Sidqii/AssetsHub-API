<?php

header('Content-Type: application/json');
require('../connection/connect_db.php');
require('../../method/riwayat/get.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        ambilRiwayat($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tidak diizinkan']);
        break;
}