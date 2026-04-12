FROM php:8.3-cli

WORKDIR /app

# install dependencies system
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    zlib1g-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd \
    && rm -rf /var/lib/apt/lists/*

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy project
COPY . .

# install Laravel dependencies (includes maatwebsite/excel for Excel import/export)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# install Node dependencies and automatically fix npm audit issues
RUN npm install && npm audit fix || true

# run app
CMD ["sh", "-c", "php artisan storage:link && sleep 20 && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"]