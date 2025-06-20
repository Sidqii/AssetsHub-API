<?php

function ambilRiwayat($conn)
{
    if (isset($_GET['id_pengguna'])) {
        $query = "SELECT p.id, u.username, i.nama_barang, p.instansi, p.hal, p.jumlah, p.tgl_kembali, 'Menunggu' AS hasil, p.id_status, p.id_pengguna
                  FROM pengajuan p 
                  LEFT JOIN items i ON p.id_barang = i.id
                  LEFT JOIN users u ON p.id_pengguna = u.id
                  WHERE p.id_status = 1 AND p.id_pengguna = ?

                  UNION

                  SELECT r.id, u.username, i.nama_barang, r.instansi, r.hal, r.jumlah, r.tgl_kembali, s.label AS hasil, r.id_status, r.id_pengguna
                  FROM riwayat_pengajuan r
                  LEFT JOIN items i ON r.id_barang = i.id
                  LEFT JOIN users u ON r.id_pengguna = u.id
                  LEFT JOIN status s ON r.id_status = s.id
                  WHERE r.id_pengguna = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $_GET['id_pengguna'], $_GET['id_pengguna']);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    } else {
        $query = "SELECT p.id, u.username, i.nama_barang, p.instansi, p.hal, p.jumlah, p.tgl_kembali, 'Menunggu' AS hasil, p.id_status, p.id_pengguna
                  FROM pengajuan p
                  LEFT JOIN items i ON p.id_barang = i.id
                  LEFT JOIN users u ON p.id_pengguna = u.id
                  WHERE p.id_status = 1

                  UNION

                  SELECT r.id, u.username, i.nama_barang, r.instansi, r.hal, r.jumlah, r.tgl_kembali, s.label AS hasil, r.id_status, r.id_pengguna
                  FROM riwayat_pengajuan r
                  LEFT JOIN items i ON r.id_barang = i.id
                  LEFT JOIN users u ON r.id_pengguna = u.id
                  LEFT JOIN status s ON r.id_status = s.id";

        $result = $conn->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
}