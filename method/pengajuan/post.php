<?php

function pengajuanBarang($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id_pengguna'], $data['id_barang'], $data['jumlah'], $data['tgl_pinjam'], $data['tgl_kembali'], $data['status'])) {
        $query = 'INSERT INTO loans (id_pengguna, id_barang, jumlah, tgl_pinjam, tgl_kembali, status) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiisss', $data['id_pengguna'], $data['id_barang'], $data['jumlah'], $data['tgl_pinjam'], $data['tgl_kembali'], $data['status']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Pengajuan barang berhasil']);
        } else {
            echo json_encode(['error' => 'Gagal mengajukan barang']);
        }
    } else {
        echo json_encode(['error' => 'Data tidak lengkap']);
    }
}