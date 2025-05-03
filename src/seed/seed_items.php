<?php
$mysqli = new mysqli("localhost", "root", "", "assetshub");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Daftar fix barang tanpa duplikat
$items = [
    [
        'nama' => 'Laptop Asus',
        'id_kategori' => 1,
        'merk' => 'Asus',
        'stok' => 5,
        'harga' => 12000000
    ],
    [
        'nama' => 'Printer Epson',
        'id_kategori' => 1,
        'merk' => 'Epson',
        'stok' => 3,
        'harga' => 4000000
    ],
    [
        'nama' => 'Meja Kantor',
        'id_kategori' => 2,
        'merk' => 'IKEA',
        'stok' => 8,
        'harga' => 1500000
    ],
    [
        'nama' => 'Kertas A4 Sidu',
        'id_kategori' => 3,
        'merk' => 'Sinar Dunia',
        'stok' => 500,
        'harga' => 75000
    ],
    [
        'nama' => 'Stapler Joyko',
        'id_kategori' => 3,
        'merk' => 'Joyko',
        'stok' => 100,
        'harga' => 30000
    ],
    // Tambahkan barang lainnya...
];

$pengadaan_options = ['APBN 2021', 'APBN 2022', 'APBN 2023'];
$item_counter = 1;

foreach ($items as $barang) {

    $status = 'Baik'; // default status real
    $id_lokasi = rand(1, 3);

    $kode_merk = strtoupper(substr($barang['merk'], 0, 3));
    $no_seri = "SN-" . $kode_merk . "-" . str_pad($item_counter, 4, "0", STR_PAD_LEFT);
    $pengadaan = $pengadaan_options[array_rand($pengadaan_options)];
    $tanggal_masuk = date("Y-m-d", strtotime("2023-" . rand(1, 12) . "-" . rand(1, 28)));

    $sql = "INSERT INTO items (
        nama_barang, id_kategori, harga, stok, id_lokasi, status,
        merk, no_seri, pengadaan, tanggal_masuk
    ) VALUES (
        '{$barang['nama']}', '{$barang['id_kategori']}', '{$barang['harga']}', '{$barang['stok']}', 
        '$id_lokasi', '$status', '{$barang['merk']}', '$no_seri', '$pengadaan', '$tanggal_masuk'
    )";

    $mysqli->query($sql);

    $item_counter++;
}

echo "✅ Dummy items berhasil ditambahkan tanpa duplikat!";
$mysqli->close();
?>
