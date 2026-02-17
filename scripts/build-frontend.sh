#!/bin/bash

echo "================================"
echo "Building Frontend"
echo "================================"

# Install dependencies
echo "Installing npm dependencies..."
docker compose run --rm node npm install

# Build frontend
echo "Building frontend..."
docker compose run --rm node npm run build

# Create app directory in backend/public
echo "Copying build to backend/public/app..."
mkdir -p backend/public/app
rm -rf backend/public/app/*

# Copy build files
if [ -d "frontend/dist" ]; then
    cp -r frontend/dist/* backend/public/app/
    echo "✅ Frontend built successfully!"
    echo "Build copied to: backend/public/app/"
else
    echo "❌ Build directory not found. Check vite config."
    exit 1
fi
