<?php

function ambilPersetujuan($conn)
{
    if (isset($_GET['id'])) {
        $query = 'SELECT r.id, s.kode, u.username, r.instansi, i.nama_barang, r.tgl_kembali, r.jumlah, r.hal
                  FROM riwayat_pengajuan r
                  LEFT JOIN items i ON r.id_barang = i.id
                  LEFT JOIN users u ON r.id_pengguna = u.id
                  LEFT JOIN status s ON r.id_status = s.id
                  WHERE r.id = ?';

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
        $query = 'SELECT r.id, s.kode, u.username, r.instansi, i.nama_barang, r.tgl_kembali, r.jumlah, r.hal
                  FROM riwayat_pengajuan r
                  LEFT JOIN items i ON r.id_barang = i.id
                  LEFT JOIN users u ON r.id_pengguna = u.id
                  LEFT JOIN status s ON r.id_status = s.id';

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