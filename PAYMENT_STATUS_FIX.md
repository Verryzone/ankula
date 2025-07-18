# Fix: Payment Status Stuck di Pending

## Masalah

Payment status tetap "pending" meskipun sudah melakukan pembayaran di Midtrans. Ini biasanya terjadi karena:

1. **Webhook tidak sampai ke server** - CSRF protection atau firewall
2. **Transaction ID mismatch** - Webhook menggunakan transaction_id berbeda
3. **Server error saat processing webhook**
4. **Konfigurasi webhook URL salah di Midtrans Dashboard**

## Solusi yang Diimplementasikan

### 1. CSRF Exception untuk Webhook
**File:** `bootstrap/app.php`

```php
$middleware->validateCsrfTokens(except: [
    'payment/notification'
]);
```

### 2. Manual Status Check Button
**File:** `resources/views/pages/orders/detail.blade.php`

Menambahkan tombol "Refresh Status Pembayaran" yang bisa digunakan user untuk manual check status dari Midtrans.

### 3. Console Commands untuk Debugging

#### A. Check Payment Status
```bash
php artisan payments:check-status [order_number]
```

#### B. Test Webhook Notification
```bash
php artisan webhook:test {order_number} {status}
```

#### C. Cleanup Expired Payments
```bash
php artisan payments:cleanup-expired
```

### 4. Web-based Debug Tools

#### A. Webhook Tester
Akses: `http://localhost/webhook_tester.php`
- Test webhook endpoint
- Simulate notification
- View recent logs
- Check database status

#### B. Midtrans Debug Tool
Akses: `http://localhost/debug_midtrans.php`
- Test Midtrans connection
- Check configuration
- Test payment creation

### 5. Enhanced Webhook Handler
**File:** `app/Services/MidtransService.php`

Improved webhook handling dengan:
- Better error logging
- Transaction ID parsing
- Status validation

## Cara Mengatasi Payment Stuck

### Step 1: Check Manual Status
1. Buka halaman detail order
2. Klik tombol "Refresh Status Pembayaran"
3. Sistem akan check langsung ke Midtrans dan update status

### Step 2: Command Line Check
```bash
# Check specific order
php artisan payments:check-status INV202507181234-AB1C

# Check all pending payments
php artisan payments:check-status
```

### Step 3: Test Webhook
```bash
# Test dengan status settlement (sukses)
php artisan webhook:test INV202507181234-AB1C settlement

# Test dengan status lain
php artisan webhook:test INV202507181234-AB1C capture
```

### Step 4: Debug dengan Web Tools
1. Akses `http://localhost/webhook_tester.php`
2. Masukkan transaction ID yang bermasalah
3. Test send webhook notification

## Konfigurasi Midtrans Dashboard

Pastikan webhook URL di Midtrans Dashboard sudah benar:

### Sandbox Environment:
- **Payment Notification URL**: `https://yoursite.com/payment/notification`
- **Finish Redirect URL**: `https://yoursite.com/payment/success`
- **Unfinish Redirect URL**: `https://yoursite.com/payment/failed`
- **Error Redirect URL**: `https://yoursite.com/payment/failed`

### Production Environment:
Same URLs but with production domain.

## Troubleshooting Checklist

### ✅ Server Configuration
- [ ] Webhook URL accessible dari internet
- [ ] CSRF protection disabled untuk webhook endpoint
- [ ] Server dapat menerima POST request
- [ ] No firewall blocking Midtrans IP

### ✅ Database Check
- [ ] Payment record exists
- [ ] Transaction ID correct
- [ ] Status is 'pending'
- [ ] Snap token not expired

### ✅ Midtrans Configuration
- [ ] Server key correct
- [ ] Client key correct
- [ ] Environment setting (sandbox/production) correct
- [ ] Webhook URL configured in dashboard

### ✅ Logs
- [ ] Check Laravel log: `storage/logs/laravel.log`
- [ ] Look for Midtrans notification logs
- [ ] Check for PHP errors

## Quick Fix Commands

### Update All Pending Payments
```bash
# Check and update all pending payments
php artisan payments:check-status

# Cleanup expired tokens
php artisan payments:cleanup-expired
```

### Manual Webhook Test
```bash
# Replace with actual order number
php artisan webhook:test INV202507181234-AB1C settlement
```

### Check Specific Order
```bash
# Get detailed info about specific order
php artisan payments:check-status INV202507181234-AB1C
```

## Prevention

### 1. Scheduled Tasks
Add to `app/Console/Kernel.php` (if exists) or create scheduled job:

```php
$schedule->command('payments:check-status')->hourly();
$schedule->command('payments:cleanup-expired')->daily();
```

### 2. Monitoring
- Setup monitoring untuk stuck payments
- Alert jika terlalu banyak pending payments > 1 hour
- Regular check webhook connectivity

### 3. User Interface
- Add manual refresh button di order detail
- Show clear payment instructions
- Provide customer service contact

## Testing

### Test Webhook Endpoint
```bash
curl -X POST http://localhost:8000/payment/notification \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "INV202507181234-AB1C-1721234567-xyz123",
    "transaction_status": "settlement",
    "fraud_status": "accept"
  }'
```

### Expected Response
```json
{"status": "success"}
```

## Contact for Support

Jika masih ada masalah:
1. Check log di `storage/logs/laravel.log`
2. Use debug tools di `/webhook_tester.php`
3. Run manual check command
4. Verify Midtrans dashboard configuration

## Status Flow

```
User Payment → Midtrans → Webhook → Laravel → Database Update
                    ↓
              If webhook fails
                    ↓
            Manual Status Check → Update Database
```

Payment status akan otomatis terupdate jika webhook berhasil, atau bisa diupdate manual menggunakan tools yang disediakan.
