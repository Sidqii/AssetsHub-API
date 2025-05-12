<?php

header('Connection-Types: application/json');
require('../connection/connect_db.php');
require('../../method/persetujuan/patch.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'PATCH':
        persetujuan($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tidak diizinkan']);
        break;
}
