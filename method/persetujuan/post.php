<?php

function persetujuan($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (
        isset(
        $data['id_pengguna'],
        $data['id_barang'],
        $data['jumlah'],
        $data['tgl_pinjam'],
        $data['tgl_kembali'],
        $data['id_pengajuan'],
        $data['tgl_proses'],
        $data['id_status']
    )
    ) {
        $query = 'INSERT INTO riwayat_pengajuan (id_pengguna, id_barang, jumlah, tgl_pinjam, tgl_kembali, id_pengajuan, tgl_proses, id_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'iiissisi',
            $data['id_pengguna'],
            $data['id_barang'],
            $data['jumlah'],
            $data['tgl_pinjam'],
            $data['tgl_kembali'],
            $data['id_pengajuan'],
            $data['tgl_proses'],
            $data['id_status']
        );

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Sukses']);
        } else {
            echo json_encode(['Error' => 'Gagal']);
        }
    } else {
        echo json_encode(['Error' => 'Data tidak valid']);
    }
}