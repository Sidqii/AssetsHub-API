<?php

header('Content-Type: application/json');
require('../connection/connect_db.php');
require('../../method/persetujuan/patch.php');
require('../../method/persetujuan/get.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'PATCH':
        persetujuan($conn);
        break;

    case 'GET':
        ambilPersetujuan($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tidak diizinkan']);
        break;
}
