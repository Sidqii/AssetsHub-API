<?php

function ambilPengajuan($conn)
{
    if (isset($_GET['id'])) {
        $query = 'SELECT p.id, p.id_pengguna, p.id_status, u.username, p.instansi, p.hal, i.nama_barang, p.jumlah, p.tgl_kembali
                  FROM pengajuan p
                  LEFT JOIN items i ON p.id_barang = i.id
                  LEFT JOIN users u ON p.id_pengguna = u.id
                  LEFT JOIN status s ON p.id_status = s.id
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
    } else if (isset($_GET['id_pengguna'])) {
        $query = 'SELECT p.id, p.id_pengguna, p.id_status, u.username, p.instansi, p.hal, i.nama_barang, p.jumlah, p.tgl_kembali
                  FROM pengajuan p
                  LEFT JOIN items i ON p.id_barang = i.id
                  LEFT JOIN users u ON p.id_pengguna = u.id
                  LEFT JOIN status s ON p.id_status = s.id
                  WHERE p.id_pengguna = ?';

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $_GET['id_pengguna']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $idPengajuan = [];
            while ($row = $result->fetch_assoc()) {
                $idPengajuan[] = $row;
            }
            echo json_encode($idPengajuan);
        } else {
            echo json_encode([]);
        }
    } else {
        $query = 'SELECT p.id, p.id_pengguna, p.id_status, u.username, p.instansi, p.hal, i.nama_barang, p.jumlah, p.tgl_kembali
                  FROM pengajuan p
                  LEFT JOIN items i ON p.id_barang = i.id
                  LEFT JOIN users u ON p.id_pengguna = u.id
                  LEFT JOIN status s ON p.id_status = s.id';

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $pengajuan = [];
            while ($row = $result->fetch_assoc()) {
                $pengajuan[] = $row;
            }
            echo json_encode($pengajuan);
        } else {
            echo json_encode([]);
        }
    }
}