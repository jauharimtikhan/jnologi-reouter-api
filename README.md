<h1 align="center">Paket API PHP Mikrotik</h1>

![Mikrotik API](https://img.shields.io/badge/Mikrotik-RouterOS-blue) ![PHP](https://img.shields.io/badge/PHP-%3E%3D%208.2-orange) ![License](https://img.shields.io/badge/License-MIT-green)

## 🚀 Pengantar

Paket **Mikrotik PHP API** ini bikin koneksi ke perangkat MikroTik RouterOS jadi lebih gampang pakai PHP. Paket ini dibuat buat bantu nge-otomatisasi router Mikrotik, kayak ngatur antarmuka, konfigurasi IP, cek lalu lintas jaringan, dan lainnya.

Cocok banget buat yang mau bikin alat monitoring jaringan atau otomatisasi konfigurasi, paket ini bisa bantu buat interaksi ke API MikroTik lebih simpel.

## ✨ Fitur

- 📡 **Integrasi API RouterOS Simpel**: Pembungkus API buat interaksi sama RouterOS jadi gampang.
- 🛠️ **Ngatur Konfigurasi**: Bisa bikin, edit, dan hapus konfigurasi Mikrotik (IP, firewall, antarmuka, dll.).
- 📊 **Pantau dan Analisa Lalu Lintas**: Dapetin data real-time kayak statistik lalu lintas, pemakaian resource, dan lainnya.
- 🔐 **Koneksi Aman**: Komunikasi aman ke router pakai API Mikrotik.
- ⚡ **Cepet dan Ringan**: Tukar data efisien dengan overhead minimal.

## 🛠️ Instalasi

Install paket lewat Composer:

```bash
composer require jauhar/router-api
```

Atau, download aja paketnya dan masukin kelasnya secara manual.

## 🚀 Langsung Coba

### 1. **Bikin Koneksi**

Pertama, bikin koneksi ke perangkat Mikrotik RouterOS pakai API atau RestFullAPI.

```php
<?php

require('vendor/autoload.php'); // Include lewat Composer

use Jnologi\RouterApi\Core;
use Jnologi\RouterApi\RestApi;

$api = Core::config('{ip_router}', '{username}', '{password}');

$api->setPort('{set_port_mikrotik}') // default-nya 8728

$response = $api->query('/interface/print');
print_r($response);

//  Metode pakai RestFullAPI
$rest = RestApi::init('{ip_router}', '{username}', '{password}');
$rest->setPort('{your_mikrotik_port}') // Defaultnya 80

$resultApi = $rest->get('{url}') // Metode GET
$resultApi = $rest->add('{url}', $data) // Metode PUT
$resultApi = $rest->update('{url}', $data) // Metode PATCH
$resultApi = $rest->delete('{url}') // Metode DELETE

echo "<pre>";
print_r($resultApi);
echo "</pre>";
?>
```

### 2. **Ambil Info Antarmuka Pakai Parameter**

Ambil daftar semua antarmuka dari router dengan parameter query:

```php
<?php

$response = $api->where('/interface/print', [
    '?name' => 'ether1'
]);

print_r($response);
?>
```

## 📚 Referensi API

### Tinjauan Metode

- `getById($host, $username, $password)`  
  Buat koneksi ke perangkat Mikrotik RouterOS.
- `query($command)`  
  Kirim perintah ke router. Pakai `false` buat kirim beberapa bagian dari perintah yang sama.
- `where($command, ...$params)`  
  Baca respons dari RouterOS.
- `disconnect()`  
  Tutup koneksi sama API RouterOS.

### Perintah Umum

- `/interface/print`  
  Ambil daftar antarmuka.
- `/ip/address/add`  
  Tambahin alamat IP baru ke antarmuka.

- `/ip/address/remove`  
  Hapus alamat IP dari router.

  <a href="command.md" >Lihat Perintah Lainnya di Sini</a>

### Contoh Penggunaan

- **Ambil statistik lalu lintas**:

  ```php
  $api->where('/interface/monitor-traffic', [
    '?interface' => 'ether1',
    '?once' => true
  ]);
  ```

## 🛡️ Keamanan

Pastikan layanan API Mikrotik diaktifin dengan aman dan koneksi API-nya nggak kebuka ke jaringan publik. Disarankan pakai **SSH** atau VPN kalau kerja pakai API.

## 🧪 Testing

API bisa dites pakai lingkungan pengembangan lokal atau server web PHP. Pastikan udah:

- Aktifin layanan API Mikrotik di router.
- Aturan firewall yang bener buat ngizinin akses ke port API.

## 🤝 Kontribusi

Kontribusi dibuka lebar! Jangan sungkan buat kirim pull request atau buka masalah buat bug atau fitur baru.

1. Fork repo-nya.
2. Bikin cabang baru buat fitur atau perbaikan.
3. Kirim pull request dan jelasin perubahannya.

## 📄 Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT. Lihat file [LICENSE](LICENSE) buat detail.

---

## 💡 Butuh Bantuan?

Kalau ada masalah, jangan ragu buat kontak dengan bikin isu di repo GitHub atau mampir ke [Forum Mikrotik](https://forum.mikrotik.com/).

---

### Dibuat dengan ❤️ oleh Jauhar Imtikhan

---

Selamat membangun alat otomasi jaringan kamu pakai Mikrotik PHP API!

---
