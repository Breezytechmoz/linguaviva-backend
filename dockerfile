FROM php:8.2-apache

# Instalar dependências do sistema e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar o código do projeto
COPY . .

# Instalar as dependências do Laravel (sem as de desenvolvimento)
RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões das pastas de storage e cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Ativar o mod_rewrite do Apache
RUN a2enmod rewrite

# Copiar o script de arranque
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expor a porta 80 (o Apache usa a 80 por defeito)
EXPOSE 80

# Comando de arranque
CMD ["/usr/local/bin/start.sh"]