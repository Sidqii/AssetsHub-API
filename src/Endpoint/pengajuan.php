<?php

header('Content-Type: application/json');
require('../connection/connect_db.php');
require('../../method/pengajuan/post.php');
require('../../method/pengajuan/get.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        pengajuanBarang($conn);
        break;

    case 'GET':
        ambilPengajuan($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tidak diizinkan']);
        break;
}
