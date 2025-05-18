<?php

function ambilryawat($conn)
{
    if (isset($_GET['id'])) {
        $query = 'SELECT p.id, r.id, s.kode, p.instansi, u.username, p.hal, p.nama_barang, p.jumlah, p.tgl_kembali
                  FROM riwayat_pengajuan r
                  LEFT JOIN pengajuan p ON r.id_pengajuan = p.id
                  LEFT JOIN status s ON r.id_status = s.id
                  LEFT JOIN users u ON r.id_pengguna = u.id
                  LEFT JOIN items i ON r.id_barang = i.id
                  WHERE r.id = ?';

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Tidak ada riwayat']);
        }
    } else {
        $query = 'SELECT p.id, r.id, s.kode, p.instansi, u.username, p.hal, p.nama_barang, p.jumlah, p.tgl_kembali
                  FROM riwayat_pengajuan r
                  LEFT JOIN pengajuan p ON r.id_pengajuan = p.id
                  LEFT JOIN status s ON r.id_status = s.id
                  LEFT JOIN users u ON r.id_pengguna = u.id
                  LEFT JOIN items i ON r.id_barang = i.id';

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $riwayat = [];
            while ($row = $result->fetch_assoc()) {
                $riwayat[] = $row;
            }
            echo json_encode($riwayat);
        } else {
            echo json_encode([]);
        }
    }
}