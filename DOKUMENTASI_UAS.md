# Dokumentasi UAS Pemrograman Web Lanjut

**Pengembangan Capstone Project: Promo Akhir Tahun**

Dokumen ini berisi penjelasan detail mengenai perbaikan database, struktur tabel, fungsi helper, serta alur bisnis proses checkout sesuai dengan lembar soal ujian akhir semester (UAS) Genap 2025/2026.

---

## 1. Perbaikan Database & Sinkronisasi Migrasi

### Masalah yang Ditemukan

Sebelumnya, data pesanan gagal masuk ke database karena terjadi error `Unknown column 'biaya_jasa' in 'field list'` saat melakukan checkout. Ini disebabkan karena status migrasi `2026-06-25-055540_AddDiskonToTransaction` sudah tercatat di tabel `migrations`, tetapi secara fisik kolom-kolom baru belum ditambahkan ke dalam tabel `transaction` di database MySQL.

### Solusi / Langkah Perbaikan

1. **Reset Status Migrasi**: Kami menghapus record migrasi versi `2026-06-25-055540` dari tabel `migrations` agar CodeIgniter mendeteksi kembali migrasi tersebut sebagai migrasi yang belum dijalankan.
2. **Eksekusi Migrasi Ulang**: Menjalankn perintah migrasi via CLI:
   ```bash
   php spark migrate
   ```
3. **Hasil Perbaikan**: Kolom-kolom promo baru berhasil ditambahkan secara aman ke dalam tabel `transaction` dan transaksi database saat checkout kini berjalan lancar tanpa rollback.

---

## 2. Struktur Kolom Database Baru (`transaction` Table)

Tabel `transaction` telah diperbarui dengan kolom-kolom berikut sesuai instruksi soal halaman 2:

| Field            | Type               | Keterangan                                                               |
| :--------------- | :----------------- | :----------------------------------------------------------------------- |
| `biaya_jasa`     | `DOUBLE NULL`      | Nilai biaya jasa (1% jika total $\le$ 10jt, atau 2% jika total $>$ 10jt) |
| `voucher_code`   | `VARCHAR(20) NULL` | Kode voucher yang digunakan oleh customer                                |
| `diskon_voucher` | `DOUBLE NULL`      | Nilai nominal diskon yang didapat dari voucher                           |
| `free_mouse`     | `DOUBLE NULL`      | Nilai free mouse (Rp 150.000 jika total belanja $>$ 15jt)                |

---

## 3. Penjelasan Fungsi Helper (`app/Helpers/DiskonHelper.php`)

Logika bisnis untuk menghitung promo dan biaya jasa ditempatkan secara modular di file `DiskonHelper.php`:

### A. Biaya Jasa (`hitung_biaya_jasa`)

Digunakan untuk menghitung tarif biaya pelayanan berdasarkan total harga belanja.

```php
function hitung_biaya_jasa($total_harga)
{
    $total_harga = (float) $total_harga;
    if ($total_harga <= 10000000) {
        $biaya_jasa = $total_harga * 0.01;
    } else {
        $biaya_jasa = $total_harga * 0.02;
    }

    return (float) $biaya_jasa;
}
```

### B. Persentase Voucher (`hitung_persen_voucher`)

Memetakan kode voucher yang dimasukkan pengguna ke persentase diskonnya.

```php
function hitung_persen_voucher($voucher_code)
{
    $voucher_code = strtoupper(trim((string) $voucher_code));

    $daftar_voucher = [
        'PROMO2025'  => 10, // Diskon 10%
        'PROMO2026'  => 15, // Diskon 15%
        'AKHIRTAHUN' => 25, // Diskon 25%
    ];

    return $daftar_voucher[$voucher_code] ?? 0; // Jika kode tidak valid -> diskon = 0%
}
```

### C. Nominal Diskon Voucher (`hitung_diskon_voucher`)

Menghitung nilai diskon dalam bentuk rupiah berdasarkan persentase voucher dari total harga pembelian.

```php
function hitung_diskon_voucher($total_harga, $voucher_code) {
    $persen = 0;

    $kode = strtoupper(trim($voucher_code));

    if ($kode == 'PROMO2025') {
        $persen = 0.10; // 10%
    } elseif ($kode == 'PROMO2026') {
        $persen = 0.15; // 15%
    } elseif ($kode == 'AKHIRTAHUN') {
        $persen = 0.25; // 25%
    }

    return $total_harga * $persen;
}
```

### D. Hadiah Langsung (`hitung_free_mouse`)

Menentukan apakah customer berhak mendapatkan hadiah mouse gratis senilai Rp 150.000.

```php
function hitung_free_mouse($total_harga) {
    if ($total_harga > 15000000) {
        return 150000;
    }
    return 0;
}
```

---

## 4. Alur Bisnis Pemesanan (`TransaksiController::buy`)

Ketika formulir checkout dikirimkan ke `/buy`, alur backend memproses data sebagai berikut:

1. **Inisialisasi Transaksi Database**: Menggunakan `$db->transStart()` untuk memastikan data transaksi utama dan detail transaksi tersimpan secara atomik (jika salah satu gagal, semuanya batal/roll back).
2. **Kalkulasi Sisi Server (Backend Validation)**:
   - Mengambil ongkir dari input data kurir.
   - Menghitung subtotal keranjang belanja asli.
   - Memanggil fungsi helper `hitungPromo()` yang membungkus semua logic di `DiskonHelper.php` to mendapatkan nilai final: `biaya_jasa`, `diskon_voucher`, dan `free_mouse`.
3. **Penyimpanan Transaksi Utama**:
   ```php
   $transaction = [
       'username'       => $this->request->getPost('username'),
       'alamat'         => $this->request->getPost('alamat'),
       'ongkir'         => $ongkir,
       'biaya_jasa'     => $promo['biaya_jasa'],
       'voucher_code'   => $promo['voucher_code'],
       'diskon_voucher' => $promo['diskon_voucher'],
       'free_mouse'     => $promo['free_mouse'],
       'total_harga'    => $promo['subtotal_promo'] + $ongkir,
       'status'         => 0, // Belum Selesai
   ];
   $this->transactionModel->insert($transaction);
   ```
4. **Penyimpanan Transaksi Detail**: Loop data barang dari keranjang belanja (`$this->cart->contents()`) untuk dimasukkan ke tabel `transaction_detail` dengan mereferensikan `transaction_id` yang baru saja dibuat.
5. **Penyelesaian Transaksi & Reset Keranjang**: Jika semua query berhasil, keranjang belanja dikosongkan (`$this->cart->destroy()`) dan pengguna dialihkan kembali ke beranda.

---

## 5. Tampilan Halaman Checkout & Riwayat Transaksi

- **Halaman Checkout (`v_checkout.php`)**:
  - Menggunakan pustaka Select2 untuk melakukan pencarian daerah tujuan (RajaOngkir).
  - JavaScript dinamis menghitung estimasi secara real-time saat pengguna mengganti pilihan kurir, kelurahan tujuan, maupun saat mengetikkan kode voucher (`PROMO2025`/`PROMO2026`/`AKHIRTAHUN`).
- **Halaman Riwayat (`v_history.php`)**:
  - Menampilkan ringkasan seluruh riwayat pembelian pengguna.
  - Terdapat tombol **Detail** yang membuka modal interaktif untuk melihat item produk yang dibeli, beserta rincian Biaya Jasa, Diskon Voucher, Free Mouse, dan Ongkir yang tersimpan aman di database.
