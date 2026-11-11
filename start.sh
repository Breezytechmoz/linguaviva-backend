#!/bin/bash

echo "A limpar caches antigas..."
php artisan optimize:clear

echo "A criar atalho do storage (para as imagens)..."
# Remove o link antigo se existir e cria um novo
rm -f public/storage
php artisan storage:link

echo "A correr migrações..."
php artisan migrate --force

echo "A correr seeders..."
php artisan db:seed --class=LanguageSeeder --force

echo "A otimizar configurações e views..."
# NÃO usar route:cache porque tens closures no api.php
php artisan config:cache
php artisan view:cache

echo "A ajustar permissões..."
# Dá permissões para o Apache conseguir ler as imagens enviadas
chmod -R 777 storage bootstrap/cache

echo "A arrancar o servidor..."
apache2-foreground