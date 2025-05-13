<?php

function ambilPersetujuan($conn)
{
    if (isset($_GET['id'])) {
        $query = 'SELECT u.username, riwayat_p.instansi, p.hal, i.nama_barang, p.jumlah, p.tgl_kembali
                  FROM riwayat_pengajuan p
                  LEFT JOIN items i ON p.id_barang = i.id
                  LEFT JOIN users u ON p.id_pengguna = u.id
                  WHERE p.id = ?';

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
        $query = 'SELECT u.username, p.instansi, p.hal, i.nama_barang, p.jumlah, p.tgl_kembali
                  FROM riwayat_pengajuan p
                  LEFT JOIN items i ON p.id_barang = i.id
                  LEFT JOIN users u ON p.id_pengguna = u.id';

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $setuju = [];
            while ($row = $result->fetch_assoc()) {
                $setuju[] = $row;
            }
            echo json_encode($setuju);
        } else {
            echo json_encode([]);
        }
    }
}