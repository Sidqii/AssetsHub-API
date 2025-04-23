<?php

function ambilBarang($conn){
    if (isset($_GET['id'])) {
        $query = 'SELECT items.id, items.nama_barang, categories.nama_kategori, items.harga, items.stok, locations.nama_lokasi, items.status, items.created_at
                  FROM items
                  LEFT JOIN categories ON items.id_kategori = categories.id
                  LEFT JOIN locations ON items.id_lokasi = locations.id
                  WHERE items.id = ?';

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
        $query = 'SELECT items.id, items.nama_barang, categories.nama_kategori, items.harga, items.stok, locations.nama_lokasi, items.status, items.created_at
                  FROM items
                  LEFT JOIN categories ON items.id_kategori = categories.id
                  LEFT JOIN locations ON items.id_lokasi = locations.id';
        
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