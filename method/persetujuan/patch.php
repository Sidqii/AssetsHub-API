<?php

function persetujuan($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["id_pengajuan"], $data["patch"])) {
        echo json_encode(["error" => "Data tidak lengkap"]);
        return;
    }

    $id = $data["id_pengajuan"];

    $stmt = $conn->prepare("SELECT * FROM pengajuan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Pengajuan tidak ditemukan"]);
        return;
    }

    $pengajuan = $result->fetch_assoc();
    $stmt->close();

    foreach ($data["patch"] as $operation) {
        if ($operation["op"] === "replace") {
            $field = trim($operation["path"], "/");

            if ($field !== "id_status") {
                echo json_encode(["error" => "Field '$field' tidak diperbolehkan diubah"]);
                return;
            }

            $pengajuan[$field] = $operation["value"];
        }
    }

    if ($pengajuan['id_status'] === 2) {
        $kurangi = $conn->prepare("UPDATE items SET stok = stok - ? WHERE id = ?");
        $kurangi->bind_param("ii", $pengajuan['jumlah'], $pengajuan['id_barang']);
        $kurangi->execute();
        $kurangi->close();
    }

    $insert = $conn->prepare("INSERT INTO riwayat_pengajuan 
        (id_pengguna, id_barang, jumlah, tgl_pinjam, tgl_kembali, id_pengajuan, tgl_proses, id_status, instansi, hal)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)");

    $insert->bind_param(
        "iiississs",
        $pengajuan['id_pengguna'],
        $pengajuan['id_barang'],
        $pengajuan['jumlah'],
        $pengajuan['tgl_pinjam'],
        $pengajuan['tgl_kembali'],
        $pengajuan['id'],
        $pengajuan['id_status'],
        $pengajuan['instansi'],
        $pengajuan['hal']
    );

    if ($insert->execute()) {
        $hapus = $conn->prepare("DELETE FROM pengajuan WHERE id = ?");
        $hapus->bind_param("i", $id);
        $hapus->execute();
        $hapus->close();

        echo json_encode(["message" => "Pengajuan berhasil diproses"]);
    } else {
        echo json_encode(["error" => "Gagal menyimpan riwayat"]);
    }

    $insert->close();
}
