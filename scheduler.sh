#!/bin/bash

# Laravel Scheduler Script
# File: /var/www/ankulaa/scheduler.sh
# Untuk menjalankan Laravel scheduler di production

# Set working directory
cd /var/www/ankulaa

# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Run Laravel scheduler
php artisan schedule:run >> storage/logs/scheduler.log 2>&1

# Optional: Clean old logs (keep last 30 days)
find storage/logs -name "*.log" -type f -mtime +30 -delete

# Add timestamp to log
echo "[$(date)] Laravel scheduler executed" >> storage/logs/scheduler-cron.log
