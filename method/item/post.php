<?php

function tambahBarang($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['nama_barang'], $data['id_kategori'], $data['stok'])) {
        $cek_kategori = $conn->prepare('SELECT id FROM categories WHERE id = ?');
        $cek_kategori->bind_param('i', $data['id_kategori']);
        $cek_kategori->execute();
        $cek_kategori->store_result();

        if ($cek_kategori->num_rows == 0) {
            echo json_encode(['error' => 'Kategori tidak ditemukan']);
            exit();
        }

        $query = 'INSERT INTO items (nama_barang, id_kategori, harga, stok, id_lokasi, status) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'sidiss',
            $data['nama_barang'],
            $data['id_kategori'],
            $data['harga'],
            $data['stok'],
            $data['id_lokasi'],
            $data['status']
        );

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Barang berhasil ditambahkan']);
        } else {
            echo json_encode(['error' => 'Gagal menambahkan barang']);
        }

    } else {
        echo json_encode(['error' => 'Data tidak lengkap']);
    }
}