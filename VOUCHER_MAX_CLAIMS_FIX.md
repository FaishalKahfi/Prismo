# Fix Maksimal Klaim Voucher

## Masalah yang Diperbaiki

1. ❌ Field `max_claims` tidak tersimpan saat admin membuat voucher (selalu NULL)
2. ❌ Field `claimed_count` tidak terupdate saat customer claim voucher (selalu 0)
3. ❌ Validasi max_claims tidak berfungsi di available vouchers
4. ❌ Customer masih bisa claim voucher meskipun sudah melebihi max_claims

## Perubahan yang Dilakukan

### 1. API VoucherController (`app/Http/Controllers/Api/VoucherController.php`)

#### Method `store()`:

-   ✅ Tambah `max_claims` di validation rules
-   ✅ Inisialisasi `claimed_count = 0` saat create voucher baru

#### Method `update()`:

-   ✅ Tambah `max_claims` di validation rules

#### Method `available()`:

-   ✅ Tambah filter untuk cek `max_claims` vs `claimed_count`
-   ✅ Voucher tidak akan muncul di available jika `claimed_count >= max_claims`

### 2. Customer VoucherController (`app/Http/Controllers/Customer/VoucherController.php`)

#### Method `index()`:

-   ✅ Tambah filter max_claims untuk available vouchers

#### Method `claim()`:

-   ✅ Tambah validasi max_claims sebelum claim
-   ✅ Tambah `$voucher->increment('claimed_count')` setelah berhasil claim

### 3. Admin KelolaVoucherController (`app/Http/Controllers/Admin/KelolaVoucherController.php`)

#### Method `index()`:

-   ✅ Tambah `max_claims` dan `claimed_count` di response data

### 4. View & JavaScript

#### Blade Template (`resources/views/admin/kelolavoucher/kelolavoucher.blade.php`):

-   ✅ Tambah kolom "Klaim" di tabel voucher

#### JavaScript (`public/js/kelolavoucher.js`):

-   ✅ Update `renderVouchers()` untuk menampilkan `claimed_count / max_claims`
-   ✅ Tampilkan "∞" (infinity) jika max_claims tidak diset

### 5. Script Perbaikan Data (`fix-voucher-claims.php`)

-   ✅ Script untuk memperbaiki data existing
-   ✅ Menghitung ulang `claimed_count` berdasarkan data actual di `user_vouchers`

## Cara Menggunakan

### 1. Jalankan Script Perbaikan Data (Sekali Saja)

```bash
php fix-voucher-claims.php
```

### 2. Membuat Voucher dengan Max Claims

1. Login sebagai Admin
2. Buka menu "Kelola Voucher"
3. Isi form voucher
4. Di field "Maksimal Klaim", masukkan angka (misal: 2)
5. Simpan voucher

### 3. Monitoring Klaim

-   Di tabel voucher admin, akan muncul kolom "Klaim"
-   Contoh tampilan: "2 / 5" (2 user sudah claim dari maksimal 5)
-   Contoh tampilan: "10 / ∞" (10 user sudah claim, unlimited)

## Testing

### Test Case 1: Buat Voucher dengan Max Claims

1. ✅ Buat voucher dengan max_claims = 2
2. ✅ Cek database: `max_claims` = 2, `claimed_count` = 0

### Test Case 2: Customer Claim Voucher

1. ✅ Customer 1 claim voucher → `claimed_count` = 1
2. ✅ Customer 2 claim voucher → `claimed_count` = 2
3. ✅ Customer 3 coba claim → Error: "Voucher sudah mencapai batas maksimal klaim"

### Test Case 3: Available Vouchers

1. ✅ Voucher dengan `claimed_count` < `max_claims` → muncul di list
2. ✅ Voucher dengan `claimed_count` >= `max_claims` → tidak muncul di list

### Test Case 4: Unlimited Claims

1. ✅ Buat voucher tanpa isi max_claims → `max_claims` = NULL
2. ✅ Customer bisa claim berapa kali pun
3. ✅ Display di admin: "X / ∞"

## Database Schema

```sql
-- Table: vouchers
ALTER TABLE vouchers ADD COLUMN max_claims INT NULL;
ALTER TABLE vouchers ADD COLUMN claimed_count INT DEFAULT 0;
```

Field ini sudah ada di Model Voucher (`$fillable` dan `$casts`).

## Notes

-   Field `max_claims` bersifat opsional (nullable)
-   Jika `max_claims` = NULL → unlimited claims
-   Field `claimed_count` auto increment setiap kali ada user claim
-   Voucher tidak auto-delete saat mencapai max_claims (berbeda dengan behavior di API VoucherController yang auto-delete)
