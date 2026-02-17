#!/bin/bash

echo "================================"
echo "Starting Streamer Development"
echo "================================"

# Start database first
echo "Starting database..."
docker compose up -d db
sleep 5

# Build and start backend
echo "Building backend..."
docker compose build backend
docker compose up -d backend nginx

# Install PHP dependencies
echo "Installing PHP dependencies..."
docker compose exec -T backend composer install

# Copy .env if not exists
if [ ! -f backend/.env ]; then
    echo "Creating .env file..."
    cp backend/.env.example backend/.env
fi

# Generate app key if needed
echo "Generating application key..."
docker compose exec -T backend php artisan key:generate || true

# Update .env for MySQL
echo "Configuring database connection..."
docker compose exec -T backend sh -c "sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env"
docker compose exec -T backend sh -c "sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=db/' .env"
docker compose exec -T backend sh -c "sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env"
docker compose exec -T backend sh -c "sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=streamer/' .env"
docker compose exec -T backend sh -c "sed -i 's/# DB_USERNAME=root/DB_USERNAME=streamer/' .env"
docker compose exec -T backend sh -c "sed -i 's/# DB_PASSWORD=/DB_PASSWORD=streamer/' .env"

# Wait for database
echo "Waiting for database..."
sleep 10

# Run migrations and seeders
echo "Running migrations..."
docker compose exec -T backend php artisan migrate:fresh --seed

# Build frontend
echo "Building frontend..."
./scripts/build-frontend.sh

# Set permissions
echo "Setting permissions..."
docker compose exec -T backend chown -R www-data:www-data /var/www/backend/storage
docker compose exec -T backend chown -R www-data:www-data /var/www/backend/bootstrap/cache

echo ""
echo "================================"
echo "✅ Development environment ready!"
echo "================================"
echo ""
echo "Frontend + Backend: http://localhost:8080"
echo ""
echo "Demo users:"
echo "  Admin: admin@streamer.local / admin12345"
echo "  User1: user1@streamer.local / user12345"
echo "  User2: user2@streamer.local / user12345"
echo ""
echo "To stop: docker compose down"
echo "================================"
