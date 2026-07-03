#!/bin/bash

set -e

BUCKET=$(grep ^B2_BUCKET /var/www/atlas/.env | cut -d '=' -f2)
FILENAME="atlas_$(date +%Y_%m_%d_%H%M%S).sql.gz"
LOCAL_PATH="/var/www/atlas/storage/backups/${FILENAME}"

mkdir -p /var/www/atlas/storage/backups

echo "Creating dump: ${FILENAME}"
docker compose -f /var/www/atlas/docker-compose.prod.yml exec -T postgres pg_dump -U atlas atlas | gzip > "${LOCAL_PATH}"

echo "Uploading to B2..."
rclone copy "${LOCAL_PATH}" "b2:${BUCKET}/"

echo "Cleaning up local file..."
rm "${LOCAL_PATH}"

echo "Removing old backups from B2..."
rclone delete "b2:${BUCKET}" --min-age 7d

echo "Backup complete: ${FILENAME}"
