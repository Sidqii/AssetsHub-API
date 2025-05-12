<?php

function pengajuanBarang($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (
        isset(
        $data['id_pengguna'],
        $data['id_barang'],
        $data['jumlah'],
        $data['tgl_kembali'],
        $data['instansi'],
        $data['hal']
    )
    ) {
        $tgl_pinjam = date('Y-m-d');
        $stok = null;
        $status_id = 0;
        $user_count = 0;

        $cekUser = $conn->prepare('SELECT COUNT(*) FROM users WHERE id = ?');
        $cekUser->bind_param('i', $data['id_pengguna']);
        $cekUser->execute();
        $cekUser->bind_result($user_count);
        $cekUser->fetch();
        $cekUser->close();

        if ($user_count == 0) {
            echo json_encode(['Error' => 'Pengguna tidak ditemukan']);
            return;
        }

        $cekStok = $conn->prepare('SELECT stok FROM items WHERE id = ?');
        $cekStok->bind_param('i', $data['id_barang']);
        $cekStok->execute();
        $cekStok->bind_result($stok);
        $cekStok->fetch();
        $cekStok->close();

        if ($stok < $data['jumlah']) {
            echo json_encode(['Error' => 'Barang tidak cukup']);
            return;
        }

        $getStatus = $conn->prepare("SELECT id FROM status WHERE kode = 'menunggu'");
        $getStatus->execute();
        $getStatus->bind_result($status_id);
        $getStatus->fetch();
        $getStatus->close();

        $query = 'INSERT INTO pengajuan (id_pengguna, id_barang, jumlah, tgl_pinjam, tgl_kembali, id_status, instansi, hal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            'iiisssss',
            $data['id_pengguna'],
            $data['id_barang'],
            $data['jumlah'],
            $tgl_pinjam,
            $data['tgl_kembali'],
            $status_id,
            $data['instansi'],
            $data['hal']
        );

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Pengajuan barang berhasil']);
        } else {
            echo json_encode(['error' => 'Gagal mengajukan barang']);
        }
    } else {
        echo json_encode(['error' => 'Data tidak lengkap']);
    }
}