#!/bin/bash

# Laravel Production Deployment Script
# File: deploy-production.sh
# Untuk setup otomatis di production server

echo "🚀 Starting Laravel Production Deployment..."

# 1. Set proper directory permissions
echo "📁 Setting permissions..."
sudo chown -R www-data:www-data /var/www/ankulaa
sudo chmod -R 755 /var/www/ankulaa
sudo chmod -R 775 /var/www/ankulaa/storage
sudo chmod -R 775 /var/www/ankulaa/bootstrap/cache

# 2. Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
cd /var/www/ankulaa
composer install --optimize-autoloader --no-dev

# 3. Optimize Laravel
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 4. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Setup cron job for Laravel scheduler
echo "⏰ Setting up cron job..."
# Backup existing crontab
crontab -l > /tmp/crontab_backup.txt 2>/dev/null || true

# Add Laravel scheduler to crontab if not exists
if ! crontab -l 2>/dev/null | grep -q "artisan schedule:run"; then
    echo "* * * * * cd /var/www/ankulaa && php artisan schedule:run >> /var/www/ankulaa/storage/logs/scheduler-cron.log 2>&1" | crontab -
    echo "✅ Cron job added successfully"
else
    echo "✅ Cron job already exists"
fi

# 6. Create necessary log files
echo "📝 Creating log files..."
touch /var/www/ankulaa/storage/logs/payment-auto-check.log
touch /var/www/ankulaa/storage/logs/payment-cleanup.log
touch /var/www/ankulaa/storage/logs/scheduler-cron.log
touch /var/www/ankulaa/storage/logs/scheduler-heartbeat.txt
touch /var/www/ankulaa/storage/logs/maintenance.log

# Set log file permissions
sudo chown www-data:www-data /var/www/ankulaa/storage/logs/*.log
sudo chown www-data:www-data /var/www/ankulaa/storage/logs/scheduler-heartbeat.txt
sudo chmod 664 /var/www/ankulaa/storage/logs/*

# 7. Test scheduler
echo "🧪 Testing scheduler..."
php artisan schedule:list
echo ""

# 8. Test payment commands
echo "🧪 Testing payment commands..."
php artisan payments:check-all
echo ""

# 9. Setup logrotate for Laravel logs
echo "🔄 Setting up log rotation..."
sudo tee /etc/logrotate.d/laravel > /dev/null <<EOF
/var/www/ankulaa/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 664 www-data www-data
    postrotate
        /bin/systemctl reload nginx > /dev/null 2>&1 || true
    endscript
}
EOF

# 10. Create monitoring script
echo "📊 Creating monitoring script..."
sudo tee /usr/local/bin/laravel-monitor.sh > /dev/null <<'EOF'
#!/bin/bash

# Laravel Scheduler Monitor
HEARTBEAT_FILE="/var/www/ankulaa/storage/logs/scheduler-heartbeat.txt"
CURRENT_TIME=$(date +%s)

if [ -f "$HEARTBEAT_FILE" ]; then
    HEARTBEAT_TIME=$(stat -c %Y "$HEARTBEAT_FILE")
    TIME_DIFF=$((CURRENT_TIME - HEARTBEAT_TIME))
    
    # If heartbeat is older than 5 minutes (300 seconds), alert
    if [ $TIME_DIFF -gt 300 ]; then
        echo "❌ Laravel scheduler might be down! Last heartbeat: $(date -d @$HEARTBEAT_TIME)"
        # Add your alerting logic here (email, Slack, etc.)
    else
        echo "✅ Laravel scheduler is running normally"
    fi
else
    echo "❌ Heartbeat file not found! Scheduler might not be running."
fi

# Check recent payment auto-checks
echo "📋 Recent payment checks:"
tail -5 /var/www/ankulaa/storage/logs/payment-auto-check.log
EOF

sudo chmod +x /usr/local/bin/laravel-monitor.sh

# 11. Setup monitoring cron (every 5 minutes)
if ! crontab -l 2>/dev/null | grep -q "laravel-monitor.sh"; then
    (crontab -l 2>/dev/null; echo "*/5 * * * * /usr/local/bin/laravel-monitor.sh >> /var/log/laravel-monitor.log 2>&1") | crontab -
    echo "✅ Monitoring cron job added"
fi

# 12. Restart web server
echo "🔄 Restarting web server..."
sudo systemctl reload nginx || sudo systemctl reload apache2 || true

# 13. Final status check
echo ""
echo "🎯 Deployment Summary:"
echo "=================================="
echo "✅ Permissions set"
echo "✅ Dependencies installed"
echo "✅ Laravel optimized"
echo "✅ Database migrated"
echo "✅ Cron job configured"
echo "✅ Logs configured"
echo "✅ Monitoring setup"
echo ""
echo "🔍 Check scheduler status:"
echo "crontab -l"
echo ""
echo "🔍 Monitor logs:"
echo "tail -f /var/www/ankulaa/storage/logs/payment-auto-check.log"
echo ""
echo "🔍 Check scheduler health:"
echo "/usr/local/bin/laravel-monitor.sh"
echo ""
echo "🚀 Deployment completed successfully!"
EOF
