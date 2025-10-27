# ----------------------------------------------------------------------
# STAGE 1: BUILD - Compilação de Assets e Instalação de Dependências
# ----------------------------------------------------------------------
FROM composer:2.7 as composer_stage

# Define o diretório de trabalho
WORKDIR /app

# Copia os arquivos de configuração do Composer
COPY composer.json composer.lock ./

# Instala as dependências do PHP (sem dependências de desenvolvimento)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ----------------------------------------------------------------------
# STAGE 2: NODE BUILD - Compilação de Assets Front-end
# ----------------------------------------------------------------------
FROM node:20-alpine as node_stage

# Define o diretório de trabalho
WORKDIR /app

# Copia os arquivos de configuração do Node
COPY package.json package-lock.json ./

# Instala as dependências do Node
RUN npm install

# Copia o código-fonte do projeto
COPY . .

# Compila os assets (CSS/JS) para produção
# Se o seu projeto usa 'npm run dev' ou outro comando, ajuste aqui.
RUN npm run build

# ----------------------------------------------------------------------
# STAGE 3: PRODUCTION - Imagem Final de Execução (PHP-FPM)
# ----------------------------------------------------------------------
# Usamos uma imagem PHP-FPM (FastCGI Process Manager) para rodar o Laravel
FROM php:8.2-fpm-alpine

# Instala as extensões PHP necessárias para o Laravel e utilitários
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Define o diretório de trabalho
WORKDIR /var/www/html

# Remove o código-fonte padrão da imagem
RUN rm -rf ./*

# Copia as dependências do Composer (Stage 1)
COPY --from=composer_stage /app/vendor /var/www/html/vendor

# Copia os assets compilados e o código-fonte (Stage 2)
COPY --from=node_stage /app /var/www/html

# Configura permissões para o Laravel
# O usuário 'www-data' é o padrão do PHP-FPM
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expõe a porta padrão do PHP-FPM
EXPOSE 9000

# Comando de inicialização (pode ser ajustado dependendo de como você usa o supervisor ou o Coolify)
# Este comando garante que o PHP-FPM esteja rodando.
CMD ["php-fpm"]

# ----------------------------------------------------------------------
# Configuração Adicional (Opcional, mas Recomendada)
# ----------------------------------------------------------------------
# Para rodar o Nginx junto com o PHP-FPM, você precisaria de um arquivo
# de configuração do Nginx e um sistema de inicialização como o Supervisor.
# Se você estiver usando o Coolify, ele geralmente gerencia o Nginx/Proxy
# separadamente, apontando para a porta 9000 do seu container PHP-FPM.
