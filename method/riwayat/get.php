<?php

function ambilRiwayat($conn)
{
    if (isset($_GET['id_user'])) {
        $id_user = $_GET['id_user'];

        $cekRoleQuery = 'SELECT role FROM users WHERE id = ?';
        $stmtRole = $conn->prepare($cekRoleQuery);
        $stmtRole->bind_param('i', $id_user);
        $stmtRole->execute();
        $roleResult = $stmtRole->get_result();

        if ($roleResult->num_rows > 0) {
            $userData = $roleResult->fetch_assoc();
            $role = strtolower($userData['role']);

            if ($role === 'staff') {
                $query = 'SELECT lh.id, i.nama_barang, i.merk, i.pengadaan, l.nama_lokasi, u.nama AS peminjam, lh.tanggal_pinjam, lh.tanggal_kembali
                          FROM loan_history lh
                          JOIN i ON lh.id_barang = i.id
                          JOIN u ON lh.id_pengguna = u.id
                          LEFT JOIN l ON i.id_lokasi = l.id
                          WHERE lh.id_pengguna = ?';
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $id_user);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $riwayat = [];
                    while ($row = $result->fetch_assoc()) {
                        if (is_null($row['tanggal_kembali'])) {
                            $row['status'] = 'dipinjam';
                        } else {
                            $row['status'] = 'dikembalikan';
                        }
                        $riwayat[] = $row;
                    }
                    echo json_encode($riwayat);
                } else {
                    echo json_encode([]);
                }
            } else if ($role === 'operator') {
                $query = 'SELECT lh.id, i.nama_barang, i.merk, i.pengadaan, l.nama_lokasi, u.nama AS peminjam, lh.tanggal_pinjam, lh.tanggal_kembali
                          FROM loan_history lh
                          JOIN i ON lh.id_barang = i.id
                          JOIN u ON lh.id_pengguna = u.id
                          LEFT JOIN l ON i.id_lokasi = l.id';
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $riwayat = [];
                    while ($row = $result->fetch_assoc()) {
                        if (is_null($row['tanggal_kembali'])) {
                            $row['status'] = 'dipinjam';
                        } else {
                            $row['status'] = 'dikembalikan';
                        }
                        $riwayat[] = $row;
                    }
                    echo json_encode($riwayat);
                } else {
                    echo json_encode([]);
                }
            } else {
                echo json_encode(['error' => 'Peran tidak dikenali']);
            }
        } else {
            echo json_encode(['error' => 'User tidak ditemukan']);
        }
    } else {
        echo json_encode(['error' => 'Parameter ID wajib disertakan']);
    }
}