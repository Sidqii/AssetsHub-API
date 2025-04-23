<?php

header('Connection-Types: application/json');
require('../connection/connect_db.php');
require('../../method/pengajuan/post.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        # code...
        pengajuanBarang($conn);
        break;

    default:
        # code...
        echo json_encode(['error' => 'Metode tidak diizinkan']);
        break;
}
