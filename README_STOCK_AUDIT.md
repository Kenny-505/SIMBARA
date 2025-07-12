# Panduan Audit dan Perbaikan Stok

Dokumentasi ini menjelaskan cara mendiagnosis dan memperbaiki masalah stok barang yang tidak terupdate dalam sistem SIMBARA.

## ⚠️ Masalah yang Sering Terjadi

### 1. **Stok Tidak Dikembalikan Setelah Pengembalian**
Stok barang masih menunjukkan jumlah yang dipinjam walaupun status pengembalian sudah "selesai".

### 2. **Stok Negatif**
Stok tersedia menunjukkan angka negatif atau lebih kecil dari seharusnya.

### 3. **Stok Tersedia > Stok Total**
Kondisi tidak normal dimana stok tersedia lebih besar dari stok total.

## 🔍 Cara Mengidentifikasi Masalah

### Melalui Web Interface (SuperAdmin)

1. **Login sebagai Super Admin**
2. **Pergi ke menu Inventaris**
3. **Klik tombol "Audit Stok"** (warna kuning)
4. **Klik "Jalankan Audit"** untuk memulai pemeriksaan
5. **Review hasil audit** yang menampilkan:
   - Total barang yang diperiksa
   - Jumlah barang dengan stok konsisten
   - Jumlah barang dengan masalah stok
   - Detail masalah per barang

### Melalui Command Line

```bash
# Audit semua barang
php artisan stock:audit

# Audit barang tertentu saja
php artisan stock:audit --item-id=123

# Audit dan langsung perbaiki
php artisan stock:audit --fix
```

## 🔧 Cara Memperbaiki Masalah

### Method 1: Melalui Web Interface

1. **Jalankan audit** melalui menu Inventaris > Audit Stok
2. **Untuk masalah individual:**
   - Klik "Detail" untuk melihat riwayat transaksi
   - Klik "Hitung Ulang" untuk otomatis memperbaiki berdasarkan riwayat
   - Klik "Manual" untuk mengatur stok secara manual
3. **Untuk masalah massal:**
   - Klik "Perbaiki Semua (Hitung Ulang)" untuk memperbaiki semua sekaligus
   - Atau pilih beberapa barang dan perbaiki yang dipilih

### Method 2: Melalui Command Line (Lebih Cepat)

```bash
# Audit dan langsung perbaiki semua masalah
php artisan stock:audit --fix

# Hanya audit tanpa memperbaiki
php artisan stock:audit
```

## 📊 Penjelasan Logic Perhitungan Stok

### Formula Stok yang Benar:
```
Stok Tersedia = Stok Total - Total Dipinjam + Total Dikembalikan (kondisi baik)
```

### Detail Perhitungan:
1. **Stok Total**: Jumlah awal barang di inventaris
2. **Total Dipinjam**: Jumlah barang yang dipinjam dengan status `approved` dan `ongoing`/`returned`
3. **Total Dikembalikan**: Jumlah barang yang dikembalikan dengan kondisi selain `parah`
4. **Barang Rusak Parah**: Dikurangi dari stok total dan tidak dikembalikan ke stok tersedia

### Kondisi Barang Saat Pengembalian:
- **`baik`**: Dikembalikan ke stok tersedia
- **`ringan`**: Dikembalikan ke stok tersedia (dengan denda)
- **`sedang`**: Dikembalikan ke stok tersedia (dengan denda)
- **`parah`**: TIDAK dikembalikan, dihapus dari stok total

## 🚨 Penyebab Masalah Stok

### 1. **Duplikasi Update Stok**
Ada 2 tempat yang melakukan update stok saat pengembalian:
- `SuperAdmin/PengembalianController.php` (method `updateStockAfterReturn()`)
- `SuperAdmin/TransaksiController.php` (saat verifikasi pembayaran denda)

### 2. **Status Pengembalian Belum Selesai**
Pengembalian dengan status:
- `payment_required`: Menunggu pembayaran denda
- `payment_uploaded`: Menunggu verifikasi pembayaran
- `pending`: Belum diproses admin

**Stok hanya dikembalikan saat status menjadi `completed` atau `fully_completed`**

### 3. **Race Condition**
Jika ada 2 proses yang berjalan bersamaan saat pengembalian.

## 🔄 Status Pengembalian dan Update Stok

| Status Pengembalian | Update Stok | Keterangan |
|-------------------|-------------|------------|
| `pending` | ❌ Tidak | Belum diproses admin |
| `payment_required` | ❌ Tidak | Menunggu pembayaran denda |
| `payment_uploaded` | ❌ Tidak | Menunggu verifikasi pembayaran |
| `completed` | ✅ Ya | Pengembalian selesai (tanpa denda) |
| `fully_completed` | ✅ Ya | Pengembalian selesai (setelah denda dibayar) |

## 🛠️ Langkah Troubleshooting

### Langkah 1: Identifikasi Masalah
```bash
php artisan stock:audit
```

### Langkah 2: Cek Status Pengembalian
Pastikan pengembalian sudah berstatus `completed` atau `fully_completed`:
```sql
SELECT p.kode_peminjaman, pg.status_pengembalian, pg.total_denda
FROM pengembalian pg 
JOIN peminjaman p ON pg.id_peminjaman = p.id_peminjaman 
WHERE pg.status_pengembalian NOT IN ('completed', 'fully_completed')
ORDER BY pg.created_at DESC;
```

### Langkah 3: Perbaiki Stok
```bash
php artisan stock:audit --fix
```

### Langkah 4: Verifikasi Hasil
```bash
php artisan stock:audit
```

## 📝 Log Monitoring

Monitor log untuk error terkait stok:
```bash
tail -f storage/logs/laravel.log | grep -i "stock\|stok"
```

## 🔒 Backup Sebelum Perbaikan

**PENTING:** Selalu backup database sebelum menjalankan perbaikan massal:

```bash
# Backup tabel yang akan diubah
mysqldump -u username -p database_name barang > backup_barang.sql
```

## 📞 Bantuan Lebih Lanjut

Jika masalah masih terjadi setelah mengikuti panduan ini:

1. **Cek log error** di `storage/logs/laravel.log`
2. **Jalankan audit untuk barang spesifik** dengan `--item-id=X`
3. **Review transaksi manual** melalui interface web untuk barang bermasalah
4. **Hubungi developer** dengan menyertakan hasil audit dan log error

---

**Terakhir diupdate:** {{ date('Y-m-d H:i:s') }}  
**Versi Sistem:** SIMBARA v2.0 