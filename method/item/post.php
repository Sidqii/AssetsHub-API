<?php

function tambahBarang($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['nama_barang'], $data['id_kategori'], $data['id_lokasi'], $data['merk'], $data['no_seri'], $data['pengadaan'], $data['jum_baik'])) {

        $cek_kategori = $conn->prepare('SELECT id FROM categories WHERE id = ?');
        $cek_kategori->bind_param('i', $data['id_kategori']);
        $cek_kategori->execute();
        $cek_kategori->store_result();

        if ($cek_kategori->num_rows == 0) {
            http_response_code(404);
            echo json_encode(['error' => 'kategori tidak ditemukan']);
            exit();
        }

        $jum_baik = $data['jum_baik'];
        $jum_rusak = 0;
        $jum_rawat = 0;
        $jum_pinjam = 0;

        $stok = $jum_baik + $jum_rusak + $jum_rawat + $jum_pinjam;

        $query = 'INSERT INTO items (nama_barang, id_kategori, id_lokasi, merk, no_seri, pengadaan, stok, jum_baik, jum_rusak, jum_rawat, jum_pinjam)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'siisssiiiii',
            $data['nama_barang'],
            $data['id_kategori'],
            $data['id_lokasi'],
            $data['merk'],
            $data['no_seri'],
            $data['pengadaan'],
            $stok,
            $jum_baik,
            $jum_rusak,
            $jum_rawat,
            $jum_pinjam
        );

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['success' => 'Barang berhasil ditambahkan']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal tambah barang']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak lengkap']);
    }
}