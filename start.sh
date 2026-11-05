#!/bin/bash

# Executar as migrações da base de dados
php artisan migrate --force

# Limpar e otimizar a cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar o Apache
apache2-foreground