# Use PHP 8.2 with Apache as the base image
# Previously we only had basic PHP extensions, now we're adding more for better functionality
FROM php:8.2-apache

# Install system dependencies
# Added more dependencies compared to old version:
# - git: for version control and composer
# - libpng-dev: for GD extension (image processing)
# - libonig-dev: for mbstring extension
# - libxml2-dev: for XML processing
# - zip/unzip: for composer package management
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache to reduce image size
# This is a new optimization that wasn't in the old version
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
# Old version only had mysqli and pdo_mysql
# New version adds:
# - mbstring: for UTF-8 string handling
# - exif: for image metadata
# - pcntl: for process control
# - bcmath: for arbitrary precision mathematics
# - gd: for image processing
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache modules
# Old version only had rewrite
# Added headers module for better security control
RUN a2enmod rewrite headers

# Set working directory
# This is new and explicitly sets the correct working directory
WORKDIR /var/www/html

# Configure Apache
# This is new and allows .htaccess files to work properly
# Previously this was not configured, which could cause routing issues
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expose port 80 for Apache
EXPOSE 80