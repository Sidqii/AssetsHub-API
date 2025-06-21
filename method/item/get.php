<?php

function ambilBarang($conn){
    if (isset($_GET['id'])) {
        $query = 'SELECT i.id, i.nama_barang, k.nama_kategori, i.stok, l.nama_lokasi, i.created_at, i.merk, i.no_seri, i.pengadaan, i.jum_baik, i.jum_rusak, i.jum_rawat, i.jum_pinjam
                  FROM items i
                  LEFT JOIN categories k ON i.id_kategori = k.id
                  LEFT JOIN locations l ON i.id_lokasi = l.id
                  WHERE i.id = ?';

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Barang tidak ditemukan']);
        }
    } else {
        $query = 'SELECT i.id, i.nama_barang, k.nama_kategori, i.stok, l.nama_lokasi, i.created_at, i.merk, i.no_seri, i.pengadaan, i.jum_baik, i.jum_rusak, i.jum_rawat, i.jum_pinjam
                  FROM items i
                  LEFT JOIN categories k ON i.id_kategori = k.id
                  LEFT JOIN locations l ON i.id_lokasi = l.id';
        
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $items = [];
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            echo json_encode($items);
        } else {
            echo json_encode([]);
        }
    }
}