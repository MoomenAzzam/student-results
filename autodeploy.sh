#!/usr/bin/env bash

set -e

echo "Starting Fully Agnostic Laravel Deployment..."

# -------------------------------------------------------------
# 1. Dynamically Detect Project Path and Name
# -------------------------------------------------------------
# Get the absolute path of the directory where THIS script lives
PROJECT_PATH="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
PROJECT_NAME=$(basename "$PROJECT_PATH")

echo "Detected Project Path: $PROJECT_PATH"
echo "Detected Project Name: $PROJECT_NAME"

# Switch to the project directory securely
cd "$PROJECT_PATH"

# -------------------------------------------------------------
# 2. Detect Distro & Update System Packages
# -------------------------------------------------------------
echo "Checking and updating system packages..."
if [ -x "$(command -v apt-get)" ]; then
    sudo apt-get update -y && sudo apt-get upgrade -y
    sudo apt-get install -y git curl unzip build-essential
elif [ -x "$(command -v dnf)" ]; then
    sudo dnf upgrade --refresh -y
    sudo dnf install -y git curl unzip @development-tools
elif [ -x "$(command -v pacman)" ]; then
    sudo pacman -Syu --noconfirm
    sudo pacman -S --needed --noconfirm git curl unzip base-devel
else
    echo "Unknown package manager. Skipping OS package updates."
fi

# -------------------------------------------------------------
# 3. Pull Latest Code Safely
# -------------------------------------------------------------
echo "Fetching latest code from Git..."
# Ensure we don't get blocked by permissions or local untracked file conflicts
git reset --hard origin/main
git pull origin main

# -------------------------------------------------------------
# 4. Handle Composer (PHP Dependency Manager)
# -------------------------------------------------------------
if ! [ -x "$(command -v composer)" ]; then
    echo "Composer is missing. Installing globally..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# -------------------------------------------------------------
# 5. Ensure Node & NPM Exist (For PM2 & Potential Frontends)
# -------------------------------------------------------------
if ! [ -x "$(command -v npm)" ]; then
    echo "Node.js/NPM is missing. Installing Node.js LTS..."
    if [ -x "$(command -v apt-get)" ]; then
        curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
        sudo apt-get install -y nodejs
    elif [ -x "$(command -v dnf)" ]; then
        curl -fsSL https://rpm.nodesource.com/setup_20.x | sudo dnf -y install nodejs
    elif [ -x "$(command -v pacman)" ]; then
        sudo pacman -S --noconfirm nodejs npm
    fi
fi

# Compile frontend ONLY if package.json exists
if [ -f "package.json" ]; then
    echo "'package.json' found. Building frontend assets..."
    npm ci
    npm run build
fi

# -------------------------------------------------------------
# 6. Laravel Optimization
# -------------------------------------------------------------
echo "Optimizing Laravel application..."
chmod -R 775 storage bootstrap/cache

# Only run migrations if artisan exists (double-checking it's a standard Laravel setup)
if [ -f "artisan" ]; then
    php artisan migrate --force
    php artisan optimize
fi

# -------------------------------------------------------------
# 7. PM2 Dynamic Launch
# -------------------------------------------------------------
if ! [ -x "$(command -v pm2)" ]; then
    echo "PM2 is missing. Installing globally..."
    sudo npm install -g pm2
fi

echo "Reloading process with PM2 using project name '$PROJECT_NAME'..."

# artisan directly via PM2 using the dynamic project name!
pm2 start artisan --name "$PROJECT_NAME" --interpreter=php -- args="serve --host=127.0.0.1 --port=8000" || pm2 restart "$PROJECT_NAME"

echo "Deployment complete for $PROJECT_NAME!"
