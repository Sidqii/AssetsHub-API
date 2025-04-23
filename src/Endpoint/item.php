<?php
header('Content-Type: application/json');
require('../connection/connect_db.php');
require('../../method/item/delete.php');
require('../../method/item/patch.php');
require('../../method/item/post.php');
require('../../method/item/get.php');
require('../../method/item/put.php');
require('../../method/item/merge.php');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        tambahBarang($conn);
        break;

    case 'GET':
        ambilBarang($conn);
        break;

    case 'PUT':
        updateBarang($conn);
        break;

    case 'PATCH':
        if (isset($_GET['type']) && $_GET['type'] === 'merge') {
            patchMergeBarang($conn);
        } else {
            patchBarang($conn);
        }
        break;

    case 'DELETE':
        hapusBarang($conn);
        break;

    default:
        echo json_encode(['error' => 'Metode tidak diizinkan']);
        break;
}