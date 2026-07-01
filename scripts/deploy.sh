#!/bin/bash

# -----------------------------------------------------------------------
# Atlas Production Deploy Script
# Run this after every git push to deploy changes to production.
# Usage: bash scripts/deploy.sh
# -----------------------------------------------------------------------

set -e

echo "🚀 Deploying Atlas..."

# Pull latest code
git pull

# Install PHP dependencies
docker compose -f docker-compose.prod.yml exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader --quiet

# Run migrations
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

# Fix storage permissions
docker compose -f docker-compose.prod.yml exec -T app chown -R www-data:www-data storage
docker compose -f docker-compose.prod.yml exec -T app chmod -R 775 storage

# Clear and rebuild caches
docker compose -f docker-compose.prod.yml exec -T app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker compose -f docker-compose.prod.yml exec -T app php artisan view:cache

# Update horizon config
docker compose -f docker-compose.prod.yml exec -T horizon php artisan config:cache

# Restart horizon to pick up code changes
docker compose -f docker-compose.prod.yml restart horizon

echo "✅ Deploy complete."