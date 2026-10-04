#!/bin/bash
set -e

echo "🚀 Iniciando deploy da KL Tecnologia..."

# 1. Ativar modo de manutenção
php8.4 artisan down || true

# 2. Puxar alterações do GitHub
git checkout -- package-lock.json 2>/dev/null || true
git pull origin main

# 3. Atualizar dependências PHP
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 4. Compilar assets se houver mudanças no front
npm ci || npm install --no-save
npm run build

# 5. Executar migrações do banco
php8.4 artisan migrate --force

# 6. Recarregar caches do Laravel
php8.4 artisan config:cache
php8.4 artisan route:cache
php8.4 artisan view:cache
php8.4 artisan event:cache

# 7. Ajustar permissões
chmod -R 775 storage bootstrap/cache

# 8. Desativar modo de manutenção
php8.4 artisan up

echo "✅ Deploy concluído com sucesso!"