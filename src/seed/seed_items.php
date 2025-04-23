<?php
$mysqli = new mysqli("localhost", "root", "", "assetshub");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$kategori_items = [
    1 => ['Laptop Asus', 'Laptop Lenovo', 'Printer Epson', 'Scanner Canon', 'Proyektor BenQ',
          'Router TP-Link', 'Monitor LG', 'Speaker Logitech', 'Harddisk Eksternal', 'Webcam Logitech'],
    2 => ['Meja Kantor', 'Kursi Ergonomis', 'Lemari Arsip', 'Rak Besi', 'Filing Cabinet',
          'Meja Rapat', 'Kursi Tunggu', 'Whiteboard', 'Lemari Kayu', 'Partisi Kantor'],
    3 => ['Stapler', 'Penggaris Besi', 'Spidol Boardmaker', 'Pulpen Gel', 'Kertas A4',
          'Sticky Notes', 'Map Folder', 'Papan Klip', 'Penghapus', 'Perforator']
];

$merk_options = ['Epson', 'HP', 'Lenovo', 'Asus', 'Cisco', 'Brother', 'Dell'];
$pengadaan_options = ['APBN 2021', 'APBN 2022', 'APBN 2023'];

for ($i = 1; $i <= 30; $i++) {
    $id_kategori = rand(1, 3);
    $nama_barang = $kategori_items[$id_kategori][array_rand($kategori_items[$id_kategori])];

    // Stok berdasarkan kategori
    if ($id_kategori == 1 || $id_kategori == 2) {
        $stok = rand(1, 10);
    } else {
        $stok = rand(20, 50);
    }

    // Status berdasarkan kategori
    switch ($id_kategori) {
        case 1:
            $allowed_status = ['Baik', 'Rusak', 'Pemeliharaan', 'Dipinjam'];
            break;
        case 2:
            $allowed_status = ['Baik', 'Rusak', 'Pemeliharaan'];
            break;
        case 3:
            $allowed_status = ['Baik'];
            break;
    }
    $status = $allowed_status[array_rand($allowed_status)];

    $harga = rand(10000, 50000);
    $id_lokasi = rand(1, 3);
    $merk = $merk_options[array_rand($merk_options)];
    $kode_merk = strtoupper(substr($merk, 0, 3));
    $no_seri = "SN-" . $kode_merk . "-" . str_pad($i, 4, "0", STR_PAD_LEFT);
    $pengadaan = $pengadaan_options[array_rand($pengadaan_options)];
    $tanggal_masuk = date("Y-m-d", strtotime("2023-" . rand(1, 12) . "-" . rand(1, 28)));

    $sql = "INSERT INTO items (
        nama_barang, id_kategori, harga, stok, id_lokasi, status,
        merk, no_seri, pengadaan, tanggal_masuk
    ) VALUES (
        '$nama_barang', '$id_kategori', '$harga', '$stok', '$id_lokasi', '$status',
        '$merk', '$no_seri', '$pengadaan', '$tanggal_masuk'
    )";

    $mysqli->query($sql);
}

echo "✅ 30 dummy items berhasil ditambahkan dengan logika yang masuk akal!";
$mysqli->close();
?>
