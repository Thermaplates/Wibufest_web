#!/bin/bash

# Create required directories
mkdir -p storage/framework/{sessions,views,cache/data}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage bootstrap/cache

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link || true

# Start PHP server
php -S 0.0.0.0:${PORT:-8000} -t public
