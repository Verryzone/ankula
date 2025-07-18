# 🚀 Production Deployment Guide - Laravel Payment System

## 📋 **Deployment Checklist**

### **✅ Pre-Deployment Requirements:**
- [x] Server dengan PHP 8.1+
- [x] Composer installed
- [x] MySQL/PostgreSQL database
- [x] Nginx/Apache web server
- [x] SSL certificate configured
- [x] Domain pointing to server

### **✅ Automated Deployment:**

```bash
# 1. Upload project files to server
scp -r . user@server:/var/www/ankulaa/

# 2. Run deployment script
cd /var/www/ankulaa
chmod +x deploy-production.sh
sudo ./deploy-production.sh
```

### **✅ Manual Setup (Alternative):**

```bash
# 1. Set permissions
sudo chown -R www-data:www-data /var/www/ankulaa
sudo chmod -R 755 /var/www/ankulaa
sudo chmod -R 775 /var/www/ankulaa/storage /var/www/ankulaa/bootstrap/cache

# 2. Install dependencies
composer install --optimize-autoloader --no-dev

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database
php artisan migrate --force

# 5. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Setup cron job
crontab -e
# Add: * * * * * cd /var/www/ankulaa && php artisan schedule:run >> /dev/null 2>&1
```

## ⚡ **Automated Payment System Features:**

### **🤖 Auto-Check Payment Status:**
- **Frequency**: Setiap 3 menit
- **Function**: Check pending payments ke Midtrans
- **Auto-fix**: Update status yang sudah settlement
- **Logging**: Comprehensive payment logs

### **🧹 Auto-Cleanup:**
- **Frequency**: Setiap 2 jam
- **Function**: Cleanup expired payments
- **Maintenance**: Weekly optimization

### **💓 Health Monitoring:**
- **Heartbeat**: Every minute scheduler check
- **Monitoring**: Payment system health alerts
- **Log rotation**: Automatic log cleanup

## 🔍 **Monitoring Commands:**

### **Payment Health Check:**
```bash
# Comprehensive health monitor
php artisan payments:monitor

# Summary view only
php artisan payments:monitor --summary

# With alerts
php artisan payments:monitor --alert
```

### **Manual Payment Operations:**
```bash
# Check all pending payments
php artisan payments:check-all

# Auto-fix mismatched statuses
php artisan payments:check-all --fix

# Check specific order
php artisan payments:check-status ORDER_NUMBER

# Cleanup expired payments
php artisan payments:cleanup-expired
```

### **Scheduler Management:**
```bash
# List all scheduled tasks
php artisan schedule:list

# Test scheduler (development only)
php artisan schedule:work

# Check scheduler health
/usr/local/bin/laravel-monitor.sh
```

## 📊 **Log Monitoring:**

### **Payment Auto-Check Logs:**
```bash
tail -f storage/logs/payment-auto-check.log
```

### **Scheduler Cron Logs:**
```bash
tail -f storage/logs/scheduler-cron.log
```

### **System Health Logs:**
```bash
tail -f /var/log/laravel-monitor.log
```

### **General Laravel Logs:**
```bash
tail -f storage/logs/laravel.log | grep -i payment
tail -f storage/logs/laravel.log | grep -i midtrans
```

## 🚨 **Troubleshooting:**

### **Scheduler Not Running:**
```bash
# Check crontab
crontab -l

# Check permissions
ls -la storage/logs/

# Check heartbeat
cat storage/logs/scheduler-heartbeat.txt
stat storage/logs/scheduler-heartbeat.txt

# Manual test
php artisan schedule:run
```

### **Payments Stuck Pending:**
```bash
# Auto-fix all pending
php artisan payments:check-all --fix

# Check specific order
php artisan payments:check-status ORDER_NUMBER

# Check Midtrans connectivity
php artisan tinker --execute="echo 'Midtrans Config: ' . config('midtrans.serverKey') ? 'OK' : 'MISSING';"
```

### **Webhook Issues:**
```bash
# Check webhook logs
grep "MIDTRANS WEBHOOK" storage/logs/laravel.log

# Test webhook endpoint
curl -X POST https://yourdomain.com/payment/notification \
     -H "Content-Type: application/json" \
     -d '{"order_id":"test","transaction_status":"settlement"}'

# Check CSRF exemption
grep "payment/notification" bootstrap/app.php
```

## 🔧 **Performance Optimization:**

### **Database Optimization:**
```bash
# Index optimization
php artisan tinker --execute="
\DB::statement('CREATE INDEX idx_payments_status ON payments(status)');
\DB::statement('CREATE INDEX idx_orders_status ON orders(status)');
\DB::statement('CREATE INDEX idx_payments_created_at ON payments(created_at)');
"
```

### **Cache Configuration:**
```bash
# Redis setup (recommended)
sudo apt install redis-server
# Update .env: CACHE_DRIVER=redis QUEUE_DRIVER=redis
```

### **Queue Setup (Optional):**
```bash
# Setup queue worker
sudo nano /etc/systemd/system/laravel-worker.service

# Start queue worker
sudo systemctl enable laravel-worker
sudo systemctl start laravel-worker
```

## 📈 **Scaling Recommendations:**

### **High Traffic:**
- Use Redis for caching and sessions
- Setup queue workers for background processing
- Implement database read replicas
- Use CDN for static assets

### **Multiple Servers:**
- Shared storage for logs (NFS/S3)
- Load balancer with sticky sessions
- Centralized cron job server
- Database clustering

## 🔐 **Security Checklist:**

### **Production Security:**
- [x] `APP_DEBUG=false` in .env
- [x] `APP_ENV=production` in .env
- [x] Strong `APP_KEY` generated
- [x] Database credentials secured
- [x] Midtrans keys in .env (not hardcoded)
- [x] HTTPS enforced
- [x] File permissions set correctly
- [x] Regular backup scheduled

### **Payment Security:**
- [x] Webhook signature verification
- [x] CSRF protection configured
- [x] Input validation on all forms
- [x] Rate limiting on payment endpoints
- [x] Proper logging (no sensitive data)

## 📞 **Support Commands:**

### **Quick Health Check:**
```bash
# All-in-one health check
php artisan payments:monitor --summary && \
echo "Scheduler Status:" && \
crontab -l | grep artisan && \
echo "Recent Payments:" && \
tail -3 storage/logs/payment-auto-check.log
```

### **Emergency Payment Fix:**
```bash
# Emergency: Fix all stuck payments
php artisan payments:check-all --fix && \
php artisan optimize:clear && \
echo "System cleared and payments checked"
```

### **Backup Commands:**
```bash
# Database backup
mysqldump -u user -p database_name > backup_$(date +%Y%m%d).sql

# Application backup
tar -czf ankulaa_backup_$(date +%Y%m%d).tar.gz /var/www/ankulaa --exclude='node_modules' --exclude='vendor'
```

## 🎯 **Success Metrics:**

### **KPIs to Monitor:**
- Payment success rate > 95%
- Average payment processing time < 2 minutes
- Scheduler uptime > 99%
- Webhook delivery success rate > 90%
- Zero stuck payments > 1 hour

### **Alerting Thresholds:**
- Pending payments > 10
- Scheduler down > 5 minutes
- Payment failure rate > 10%
- Disk space < 1GB

## 🚀 **Deployment Complete!**

Your Laravel payment system is now production-ready with:
- ✅ **Fully automated payment status checking**
- ✅ **Comprehensive monitoring and alerting**
- ✅ **Robust error handling and recovery**
- ✅ **Performance optimized configuration**
- ✅ **Security hardened setup**

**Next Steps:**
1. Monitor logs for first 24 hours
2. Test payment flow end-to-end
3. Setup monitoring alerts
4. Schedule regular health checks
5. Plan backup and recovery procedures

**Support:** Use the monitoring commands above for ongoing maintenance and troubleshooting.
