# Guia de Deploy — KL Tecnologia no CloudPanel (VPS)

Este documento contém o passo a passo detalhado para realizar o deploy em produção do e-commerce **KL Tecnologia** utilizando uma VPS com o painel **CloudPanel**.

---

## 📌 Dados do Ambiente de Produção

Com base nas configurações do seu servidor e CloudPanel:

| Parâmetro | Valor Configurado |
|---|---|
| **Domínio Principal** | `kltecnologia.com` |
| **Endereço de IP da VPS** | `72.60.142.2` |
| **Usuário do Site no CloudPanel** | `kltecnologia-store` |
| **Diretório Raiz do Site** | `/home/kltecnologia-store/htdocs/kltecnologia.com` |
| **Diretório Público (Document Root)** | `/home/kltecnologia-store/htdocs/kltecnologia.com/public` |
| **Versão do PHP** | `PHP 8.4` (Template: Laravel 13) |
| **Repositório GitHub** | `https://github.com/rayhenrique/kltecnologia-store.git` |

---

## 🗺️ Visão Geral das Etapas

1. [Apontamento de DNS](#1-apontamento-de-dns)
2. [Configuração no CloudPanel (Site & Banco de Dados)](#2-configuração-no-cloudpanel)
3. [Emissão do Certificado SSL (HTTPS)](#3-emissão-do-certificado-ssl-https)
4. [Acesso SSH e Clonagem do Projeto](#4-acesso-ssh-e-clonagem-do-projeto)
5. [Instalação de Dependências (PHP & Assets)](#5-instalação-de-dependências)
6. [Configuração do Arquivo `.env` de Produção](#6-configuração-do-arquivo-env-de-produção)
7. [Banco de Dados, Migrations e Storage](#7-banco-de-dados-migrations-e-storage)
8. [Permissões de Diretórios](#8-permissões-de-diretórios)
9. [Otimização de Cache do Laravel](#9-otimização-de-cache-do-laravel)
10. [Configuração do Cron Job do Laravel](#10-configuração-do-cron-job-do-laravel)
11. [Configuração do Webhook no Mercado Pago](#11-configuração-do-webhook-no-mercado-pago)
12. [Script de Deploy Rápido para Atualizações Futuras](#12-script-de-deploy-rápido)

---

## 1. Apontamento de DNS

No painel onde você comprou o domínio (ex: Registro.br, Cloudflare, Hostinger, GoDaddy), crie os registros do tipo **A**:

| Tipo | Nome / Host | Destino / IP | TTL |
|---|---|---|---|
| **A** | `@` (ou vazio) | `72.60.142.2` | 14400 (ou Automático) |
| **A** | `www` | `72.60.142.2` | 14400 (ou Automático) |

> ⏳ **Atenção:** A propagação do DNS pode levar de 5 minutos a algumas horas. Você pode acompanhar a propagação através do site [dnschecker.org](https://dnschecker.org/#A/kltecnologia.com).

---

## 2. Configuração no CloudPanel

### 2.1. Criação do Site PHP
Você já iniciou essa etapa conforme as telas:
- **Inscrição (Template):** `Laravel 13`
- **Nome do domínio:** `kltecnologia.com`
- **Versão do PHP:** `PHP 8.4`
- **Usuário do site:** `kltecnologia-store`
- **Senha do usuário do site:** *(defina uma senha forte ou anote a gerada)*
- **Diretório raiz:** `kltecnologia.com/public` (Caminho completo: `/home/kltecnologia-store/htdocs/kltecnologia.com/public`)

### 2.2. Criação do Banco de Dados MySQL
No CloudPanel, acesse seu site `kltecnologia.com` ➔ aba **Bancos de dados** ➔ **Novo banco de dados**:
- **Nome do banco de dados:** `kltecnologia_store` (ou `kltecnologia-store`)
- **Nome de usuário do banco de dados:** `kltecnologia_store`
- **Senha do usuário do banco de dados:** *(clique em "Gerar nova senha" e salve esta senha em local seguro para colocar no `.env`)*
- Clique em **Adicionar banco de dados**.

---

## 3. Emissão do Certificado SSL (HTTPS)

Após a propagação do DNS para o IP `72.60.142.2`:
1. No CloudPanel, acesse o site `kltecnologia.com`.
2. Vá até a aba **SSL/TLS**.
3. Selecione **Novo Certificado SSL Let's Encrypt**.
4. Marque `kltecnologia.com` e `www.kltecnologia.com`.
5. Clique em **Criar e Instalar**.

---

## 4. Acesso SSH e Clonagem do Projeto

Abra o terminal do seu computador (PowerShell, Git Bash ou Linux Terminal) e conecte-se na VPS com o usuário do site:

```bash
ssh kltecnologia-store@72.60.142.2
```
*(Digite a senha definida na criação do usuário do site no CloudPanel)*.

### 4.1. Limpar arquivos padrão e Clonar o Repositório

Navegue até o diretório do site:
```bash
cd /home/kltecnologia-store/htdocs/kltecnologia.com
```

Se houver arquivos padrão criados pelo CloudPanel (como um `index.php` temporário), remova-os:
```bash
rm -rf *
rm -rf .* 2>/dev/null
```

Agora, clone o projeto diretamente na pasta atual:
```bash
git clone https://github.com/rayhenrique/kltecnologia-store.git .
```

---

## 5. Instalação de Dependências

### 5.1. Dependências do PHP (Composer)
Instale os pacotes em modo de produção (sem dependências de desenvolvimento):
```bash
composer install --no-dev --optimize-autoloader
```

### 5.2. Compilação de Assets (Vite / Tailwind)
Como o repositório ignora o diretório `public/build` por segurança, compile os assets diretamente no servidor:
```bash
npm install
npm run build
```

---

## 6. Configuração do Arquivo `.env` de Produção

Copie o arquivo de exemplo e crie o `.env`:
```bash
cp .env.example .env
```

Gere a chave da aplicação:
```bash
php8.4 artisan key:generate
```

Abra o arquivo `.env` para edição:
```bash
nano .env
```

Ajuste as configurações para o modo de produção:

```dotenv
APP_NAME="KL Tecnologia"
APP_ENV=production
APP_KEY=base64:... # (chave gerada automaticamente)
APP_DEBUG=false
APP_URL=https://kltecnologia.com
APP_TIMEZONE=America/Fortaleza
APP_LOCALE=pt_BR

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Conexão MySQL (CloudPanel)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kltecnologia_store
DB_USERNAME=kltecnologia_store
DB_PASSWORD=SUA_SENHA_GERADA_NO_CLOUDPANEL
DB_ENGINE=InnoDB

# Drivers de Sessão e Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=database
QUEUE_CONNECTION=database

# Configuração de E-mail (Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin@example.com
MAIL_PASSWORD=SUA_SENHA_DE_APP_AQUI # (Senha de App de 16 caracteres gerada no Google)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@example.com"
MAIL_FROM_NAME="KL Tecnologia"

# Mercado Pago (Produção)
MERCADO_PAGO_ACCESS_TOKEN=APP_USR-seu-token-de-producao
MERCADO_PAGO_WEBHOOK_SECRET=seu-secret-de-webhook
MERCADO_PAGO_WEBHOOK_URL=https://kltecnologia.com/webhooks/mercado-pago
MERCADO_PAGO_BASE_URL=https://api.mercadopago.com
```

> **Para salvar no Nano:** Pressione `Ctrl + O`, depois `Enter`, e para sair `Ctrl + X`.

---

## 7. Banco de Dados, Migrations e Storage

### 7.1. Executar as Migrations
Execute a criação das tabelas no MySQL da produção:
```bash
php8.4 artisan migrate --force
```

### 7.2. Usuário Administrador em Produção
A migration do projeto já provisiona automaticamente o administrador principal ao rodar o `migrate`:
- **E-mail:** `admin@example.com`
- **Senha:** `[REMOVED-ADMIN-PASSWORD]`

Se desejar alterar a senha ou recriar o usuário administrador a qualquer momento no servidor, basta rodar o comando seguro:
```bash
php8.4 artisan app:create-admin admin@example.com [REMOVED-ADMIN-PASSWORD]
```

### 7.3. Configurar Storage de Arquivos
Crie o link simbólico do storage público:
```bash
php8.4 artisan storage:link
```

Garanta que o diretório privado para os binários de produtos digitais exista:
```bash
mkdir -p storage/app/digital_products
mkdir -p storage/app/private
mkdir -p public/covers
```

---

## 8. Permissões de Diretórios

No CloudPanel, o PHP-FPM roda sob o próprio usuário do site (`kltecnologia-store`). Garanta que as pastas de escrita tenham as permissões corretas:

```bash
cd /home/kltecnologia-store/htdocs/kltecnologia.com
chmod -R 775 storage bootstrap/cache public/covers
```

---

## 9. Otimização de Cache do Laravel

Execute os comandos de cache para garantir a máxima performance em produção:

```bash
php8.4 artisan config:cache
php8.4 artisan route:cache
php8.4 artisan view:cache
php8.4 artisan event:cache
```

---

## 10. Configuração do Cron Job do Laravel

O agendador de tarefas do Laravel precisa rodar a cada minuto para processar expiração de links, filas e limpezas automáticas:

1. No CloudPanel, acesse o site `kltecnologia.com`.
2. Vá até a aba **Cron Jobs** e clique em **Adicionar Cron Job**.
3. Configure:
   - **Template / Frequência:** `A cada minuto (* * * * *)`
   - **Comando:**
     ```bash
     php8.4 /home/kltecnologia-store/htdocs/kltecnologia.com/artisan schedule:run >> /dev/null 2>&1
     ```
4. Clique em **Salvar**.

---

## 11. Configuração do Webhook no Mercado Pago

Para que os pagamentos com Pix ou Cartão sejam confirmados automaticamente:

1. Acesse o [Painel do Desenvolvedor do Mercado Pago](https://www.mercadopago.com.br/developers).
2. Vá em **Suas Aplicações** ➔ Selecione sua aplicação de produção.
3. No menu lateral, clique em **Webhooks** / **Notificações IPN**.
4. Configure a URL de produção:
   ```text
   https://kltecnologia.com/webhooks/mercado-pago
   ```
5. Marque o evento: **Pagamentos** (`payment`).
6. Copie a chave secreta gerada (*Webhook Secret*) e adicione ao seu `.env` na variável `MERCADO_PAGO_WEBHOOK_SECRET`.
7. No servidor, recarregue o cache de configurações:
   ```bash
   php8.4 artisan config:cache
   ```

---

## 12. Script de Deploy Rápido

Para facilitar futuras atualizações sempre que você fizer `git push` no repositório, você pode criar um script de deploy no servidor:

Crie o arquivo `deploy.sh` na raiz do projeto:
```bash
nano deploy.sh
```

Cole o conteúdo:
```bash
#!/bin/bash
set -e

echo "🚀 Iniciando deploy da KL Tecnologia..."

# 1. Ativar modo de manutenção
php8.4 artisan down || true

# 2. Puxar alterações do GitHub
git pull origin main

# 3. Atualizar dependências PHP
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 4. Compilar assets se houver mudanças no front
npm install
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
```

Dê permissão de execução ao script:
```bash
chmod +x deploy.sh
```

**Como atualizar o site no futuro:**
Sempre que fizer novas alterações no GitHub, basta conectar no SSH e rodar:
```bash
cd /home/kltecnologia-store/htdocs/kltecnologia.com && ./deploy.sh
```

---

## 🛡️ Checklist de Verificação Pós-Deploy

- [ ] Acessar `https://kltecnologia.com` e verificar se a página inicial carrega com SSL ativo (cadeado verde).
- [ ] Acessar `https://kltecnologia.com/catalogo` e conferir a barra lateral de filtros e cards de produtos.
- [ ] Acessar `/login` e entrar com o usuário administrador.
- [ ] Cadastrar um novo produto digital de teste (capa + arquivo zip).
- [ ] Realizar um teste de compra via checkout para validar a integração com o Mercado Pago.
- [ ] Baixar o produto pela aba "Meus Downloads" para confirmar o funcionamento da URL temporária assinada.
