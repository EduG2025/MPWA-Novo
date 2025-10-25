# -------------------------------
# Stage 1: Build
# -------------------------------
FROM php:8.2-cli AS build

# Diretório de trabalho
WORKDIR /var/www/mpwa

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev zip npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Ambiente de build
ENV APP_ENV=local

# Copia arquivos do Composer e instala dependências
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader

# Instala dependências Node.js
COPY package.json package-lock.json ./
RUN npm install

# -------------------------------
# Stage 2: Runtime
# -------------------------------
FROM php:8.2-fpm

# Diretório de trabalho
WORKDIR /var/www/mpwa

# Copia vendor e node_modules do build
COPY --from=build /var/www/mpwa/vendor ./vendor
COPY --from=build /var/www/mpwa/node_modules ./node_modules

# Copia restante do projeto
COPY . .

# Permissões Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Ambiente de produção
ENV APP_ENV=production
ENV APP_DEBUG=false

# Expondo porta PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]
