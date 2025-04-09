# Use PHP 8.2 with Apache as the base image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache modules
RUN a2enmod rewrite headers

# Set working directory
WORKDIR /var/www/html

# Configure Apache
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Add build arguments for GitHub
ARG GITHUB_REPO=https://github.com/PipouTeam/CUBE-CESI.git
ARG GITHUB_BRANCH=main

# Clone specific branch from GitHub
RUN rm -rf * && \
    git clone -b ${GITHUB_BRANCH} ${GITHUB_REPO} . && \
    rm -rf .git

EXPOSE 80