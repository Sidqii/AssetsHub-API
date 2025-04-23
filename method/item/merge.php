<?php

function patchMergeBarang($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["id"])) {
        echo json_encode(["error" => "ID barang wajib ada"]);
        return;
    }

    $id = $data["id"];
    $allowed_fields = ["nama_barang", "id_kategori", "harga", "stok", "id_lokasi", "status"];
    $updates = [];
    $params = [];
    $types = "";

    foreach ($data as $key => $value) {
        if ($key === "id") continue;
        if (!in_array($key, $allowed_fields)) {
            echo json_encode(["error" => "Field '$key' tidak diperbolehkan"]);
            return;
        }

        $updates[] = "`$key` = ?";
        $params[] = $value;
        $types .= is_numeric($value) ? "d" : "s";
    }

    if (empty($updates)) {
        echo json_encode(["error" => "Tidak ada perubahan data"]);
        return;
    }

    $params[] = $id;
    $types .= "i";

    $query = "UPDATE items SET " . implode(", ", $updates) . " WHERE id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(["error" => "Kesalahan SQL"]);
        return;
    }

    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Barang berhasil diperbarui (merge patch)"]);
    } else {
        echo json_encode(["error" => "Gagal memperbarui barang"]);
    }

    $stmt->close();
}
