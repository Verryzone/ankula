# Midtrans Payment Fix Documentation

## Masalah yang Ditemukan

Error yang muncul: `"transaction_details.order_id has already been taken"`

### Penyebab Utama:
1. **Duplikasi Order Number**: Sistem menggunakan order_number yang sama untuk multiple request
2. **Race Condition**: Multiple user melakukan checkout bersamaan
3. **Payment Retry**: User mencoba pembayaran ulang dengan transaction ID yang sama
4. **Expired Payments**: Payment yang expired tidak dibersihkan secara otomatis

## Solusi yang Diimplementasikan

### 1. Perbaikan Order Number Generation
**File:** `app/Models/Order.php`

- Menggunakan timestamp dan random string untuk memastikan uniqueness
- Format baru: `INV202507181234-AB1C` (dengan jam, menit, dan random suffix)
- Implementasi loop untuk memastikan tidak ada duplikasi

```php
public static function generateOrderNumber()
{
    do {
        $prefix = 'INV' . date('YmdHi');
        $randomSuffix = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        $orderNumber = $prefix . '-' . $randomSuffix;
        $exists = self::where('order_number', $orderNumber)->exists();
    } while ($exists);
    
    return $orderNumber;
}
```

### 2. Perbaikan Transaction ID untuk Midtrans
**File:** `app/Services/MidtransService.php`

- Membuat transaction ID yang berbeda dari order_number
- Format: `{order_number}-{timestamp}-{random}`
- Validasi payment yang sudah ada sebelum membuat yang baru

```php
$transactionId = $order->order_number . '-' . time() . '-' . substr(md5(uniqid()), 0, 6);
```

### 3. Enhanced Payment Validation
**File:** `app/Services/MidtransService.php`

- Cek payment yang sudah sukses
- Reuse snap token yang masih valid
- Hapus payment pending yang expired sebelum membuat baru

### 4. Improved Error Handling
**File:** `app/Http/Controllers/CheckoutController.php`

- Cleanup expired payments otomatis
- Better error logging
- Validasi payment existence sebelum create new

### 5. Helper Functions
**File:** `app/Helpers/Helper.php`

- `cleanupExpiredPayments()`: Membersihkan payment expired
- `generateUniqueTransactionId()`: Generate transaction ID unik

### 6. Console Command untuk Maintenance
**File:** `app/Console/Commands/CleanupExpiredPayments.php`

Command untuk membersihkan payment expired secara berkala:
```bash
php artisan payments:cleanup-expired
```

### 7. Debug Tools
**File:** `public/debug_midtrans.php`

Tool untuk debugging dan testing Midtrans integration:
- Test koneksi Midtrans
- Validasi konfigurasi
- Cek database payments
- Cleanup expired payments

## Cara Menggunakan

### 1. Testing
Akses `http://localhost/debug_midtrans.php` untuk:
- Test Midtrans connection
- Cleanup expired payments
- Validate configuration

### 2. Maintenance
Jalankan command berikut secara berkala:
```bash
php artisan payments:cleanup-expired
```

### 3. Monitoring
Monitor log Laravel untuk error:
```bash
tail -f storage/logs/laravel.log | grep -i midtrans
```

## Pencegahan di Masa Depan

### 1. Scheduled Tasks
Tambahkan ke `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('payments:cleanup-expired')->hourly();
}
```

### 2. Database Indexes
Pastikan ada index pada:
- `payments.transaction_id`
- `payments.status`
- `payments.snap_token_expires_at`

### 3. Monitoring
- Setup monitoring untuk failed payments
- Alert jika terlalu banyak expired payments
- Regular backup database

## Testing Checklist

- [ ] Order number unique setiap kali generate
- [ ] Transaction ID unique untuk Midtrans
- [ ] Payment retry menggunakan existing valid token
- [ ] Expired payments dibersihkan otomatis
- [ ] Error handling yang proper
- [ ] Log error yang informatif

## Configuration Check

Pastikan file `.env` memiliki:
```
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

## Contact for Support

Jika masih ada error:
1. Check `storage/logs/laravel.log`
2. Gunakan debug tool di `debug_midtrans.php`
3. Pastikan konfigurasi Midtrans benar
4. Cleanup expired payments terlebih dahulu
