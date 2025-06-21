<?php

function patchBarang($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["id"], $data["patch"])) {
        echo json_encode(["error" => "Format patch tidak valid"]);
        return;
    }

    $id = $data["id"];

    $cek_query = $conn->prepare("SELECT id FROM items WHERE id = ?");
    $cek_query->bind_param("i", $id);
    $cek_query->execute();
    $cek_query->store_result();

    if ($cek_query->num_rows == 0) {
        echo json_encode(['error' => 'Barang dengan id ' . $id . ' tidak dtemukan']);
        return;
    }

    $updates = [];
    $params = [];
    $types = "";

    foreach ($data["patch"] as $operation) {
        if ($operation["op"] === "replace") {
            $field = trim($operation["path"], "/");

            $allowed_fields = ["nama_barang", "id_kategori", "stok", "id_lokasi", "merk", "pengadaan", "no_seri", "jum_baik", "jum_rusak", "jum_rawat", "jum_pinjam"];
            if (!in_array($field, $allowed_fields)) {
                echo json_encode(["error" => "Field '$field' tidak diperbolehkan"]);
                return;
            }

            $value = $operation["value"];
            $updates[] = "`$field` = ?";
            $params[] = $value;
            $types .= is_numeric($value) ? "d" : "s";
        }
    }

    if (empty($updates)) {
        echo json_encode(["error" => "Tidak ada perubahan"]);
        return;
    }

    $query = "UPDATE items SET " . implode(", ", $updates) . " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo json_encode(["error" => "Kesalahan pada query SQL"]);
        return;
    }

    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Barang berhasil diperbarui"]);
    } else {
        echo json_encode(["error" => "Gagal memperbarui barang"]);
    }
}
