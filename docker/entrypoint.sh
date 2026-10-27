#!/bin/sh
set -e

# Copy environment file if not present
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Ensure application key is generated
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Wait for PostgreSQL database to be reachable
echo "Waiting for PostgreSQL database connection..."
max_retries=30
counter=0
until php -r "try { new PDO('pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }" 2>/dev/null; do
    counter=$((counter + 1))
    if [ $counter -ge $max_retries ]; then
        echo "Could not connect to database after $max_retries attempts. Exiting."
        exit 1
    fi
    echo "Database unavailable, waiting 2s ($counter/$max_retries)..."
    sleep 2
done
echo "PostgreSQL database is ready!"

# Run database migrations
php artisan migrate --force

# Create storage link if missing
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

# Clear and optimize configuration cache in dev
php artisan optimize:clear

echo "DevPulse container initialized successfully."

# Execute main process
exec "$@"
