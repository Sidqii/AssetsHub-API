<?php

function hapusBarang($conn){
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        echo json_encode(['error' => 'id barang harus disertakan']);
        return;
    }

    $id = $data['id'];

    $cek_query = 'SELECT id FROM items WHERE id = ?';
    $cekstmt = $conn->prepare($cek_query);
    $cekstmt->bind_param('i', $id);
    $cekstmt->execute();
    $cekstmt->store_result();

    if ($cekstmt->num_rows == 0) {
        echo json_encode(['error' => 'Barang tidak ditemukan']);
        return;
    }

    $query = 'DELETE FROM items WHERE id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Barang berhasil dihapus']);
    } else {
        echo json_encode(['error' => 'Barang gagal dihapus']);
    }
}