# AssetsHub API

🔧 Backend service untuk aplikasi inventaris **AssetsHub**.  
Dibuat menggunakan PHP dengan arsitektur modular, integrasi Composer,  
dan terhubung langsung dengan frontend berbasis Flutter di [InventoryApp](https://github.com/Sidqii/InventoryApp).

---

## 🚀 Fitur Utama
- CRUD Inventaris
- Pengajuan & Persetujuan Peminjaman
- Role-based Access (Staff & Operator)
- Optimasi Update Data dengan JSON Patch
- Upload File (tidak di-push ke GitHub)
- RESTful API

---

## 🗂️ Struktur Folder

```text
AssetsHubBE/
├── method/         # Fungsi-fungsi bantu / logic backend
├── src/            # Endpoint utama (GET, POST, PATCH, dsb)
├── uploads/        # Tempat file upload (tidak di-push ke GitHub)
├── vendor/         # Dependency dari Composer (di-ignore)
├── .gitignore      # Filter file sensitif & auto-generated
└── README.md
```

---

## ⚙️ Cara Menjalankan

1. Clone repo
git clone https://github.com/Sidqii/AssetsHub-API.git

2. Masuk ke folder project
cd AssetsHub-API

3. Install dependency (butuh Composer)
composer install

4. Buat config database di .env atau config.php
