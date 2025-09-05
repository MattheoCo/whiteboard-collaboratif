FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application files
COPY . .

# Run post-install scripts
RUN composer dump-autoload --optimize

# Create necessary directories and set permissions
RUN mkdir -p data var/cache var/log \
    && chmod -R 777 data var \
    && chown -R www-data:www-data data var public

# Configure Apache
RUN a2enmod rewrite headers
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set environment variables
ENV APP_ENV=prod
ENV APP_DEBUG=false
ENV DATABASE_URL="sqlite:///var/www/html/data/database.db"

# Railway uses PORT environment variable
ENV APACHE_LISTEN_PORT=${PORT:-80}
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

# Expose port
EXPOSE ${PORT:-80}

# Start Apache
CMD ["/usr/local/bin/start.sh"]
