#!/bin/sh
set -e

# Pastikan folder storage dan bootstrap/cache writable jika ada
if [ -d "/app/storage" ]; then
    chmod -R 777 /app/storage
fi

if [ -d "/app/bootstrap/cache" ]; then
    chmod -R 777 /app/bootstrap/cache
fi

# Pastikan database sqlite writable jika ada
if [ -f "/app/database/database.sqlite" ]; then
    chmod 666 /app/database/database.sqlite
fi

exec "$@"
