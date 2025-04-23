<?php

function updateBarang($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'], $data['nama_barang'], $data['id_kategori'], $data['harga'], $data['stok'], $data['id_lokasi'], $data['status'])) {
        echo json_encode(['error' => 'Data tidak lengkap']);
        return;
    }

    $id = $data['id'];

    $cek_barang = $conn->prepare('SELECT id FROM items WHERE id = ?');
    $cek_barang->bind_param('i', $id);
    $cek_barang->execute();
    $cek_barang->store_result();

    if ($cek_barang->num_rows == 0) {
        echo json_encode(['error' => 'Barang tidak ditemukan']);
        return;
    }

    $query = 'UPDATE items SET nama_barang = ?, id_kategori = ?, harga = ?, stok = ?, id_lokasi = ?, status = ? WHERE id = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        'sidissi',
        $data['nama_barang'],
        $data['id_kategori'],
        $data['harga'],
        $data['stok'],
        $data['id_lokasi'],
        $data['status'],
        $id,
    );

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Barang berhasil diperbarui']);
    } else {
        echo json_encode(['error' => 'Barang gagal diperbarui']);
    }
}