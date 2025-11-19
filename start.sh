#!/bin/bash
set -e

echo "Starting deployment..."

# Create required directories
echo "Creating directories..."
mkdir -p storage/framework/{sessions,views,cache/data}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Run seeder for default film
echo "Seeding default film..."
php artisan db:seed --class=DefaultFilmSeeder --force || echo "Seeder already run or failed"

# Create storage link
echo "Creating storage link..."
php artisan storage:link --force || true

echo "Starting server on port ${PORT:-8000}..."
# Start PHP server
exec php -S 0.0.0.0:${PORT:-8000} -t public
