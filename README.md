# KL Tecnologia — Plataforma de Produtos Digitais

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.4+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tests](https://img.shields.io/badge/Tests-65%20passed%20(208%20assertions)-success?style=for-the-badge)](tests)

Plataforma completa de e-commerce e catálogo de produtos digitais (softwares, scripts, sistemas SaaS, templates e automações) construída com **Laravel 13**, **MySQL**, **Laravel Breeze**, **Blade**, **Alpine.js** e **Tailwind CSS**.

O projeto conta com arquitetura limpa, controllers magras (*thin controllers*), serviços desacoplados para checkout e gateway (Mercado Pago), webhooks assinados idempotentes, armazenamento estritamente privado para binários de produtos com URLs assinadas temporárias, e uma suíte abrangente de 65 testes automatizados.

---

## 🌟 Principais Funcionalidades

### 🛍️ Vitrine & Catálogo Interativo
- **Home / Storefront:** Hero section com visual tecnológico (dark slate & teal), busca instantânea, carrosséis de produtos em destaque, seções de categorias, cards de vantagens e newsletter.
- **Página de Catálogo Exclusiva (`/catalogo`):** 
  - Layout moderno com barra lateral (*sidebar*) fixa de filtros: busca por termo, filtro por categoria, ordenação dinâmica (mais recentes, menor/maior preço, alfabética) e faixa de preço.
  - Grid de produtos em 3 colunas com badges de "DESTAQUE", favoritos, detalhes técnicos e botão de ação rápida.
  - Painel de status com contagem de itens ativos e paginação integrada.

### 🔐 Autenticação & Controle de Acesso
- Autenticação completa via **Laravel Breeze** (login, registro, redefinição e confirmação de senha, verificação de e-mail e gestão de perfil).
- Controle de papéis (*roles*): `customer` e `admin`.
- Middlewares de autorização (`EnsureUserIsAdmin`) e navegação contextual adaptativa.

### 💳 Checkout & Pagamento Seguro (Mercado Pago)
- Checkout unitário integrado à API de Preferências do **Mercado Pago**.
- Transição controlada de estados de pedido: `pending` ➔ `paid` / `failed`.
- Webhook dedicado (`POST /webhooks/mercado-pago`) isolado de CSRF, validado via HMAC (`x-signature`), com conferência de valor e atualização idempotente de pedidos.

### 📦 Entrega Segura de Arquivos Digitais
- Binários dos produtos armazenados em disco privado (`storage/app/digital_products`), inacessíveis via URL pública direta.
- Área **"Meus Downloads"** para clientes autenticados.
- Links de download temporários assinados criptograficamente (`URL::temporarySignedRoute`), com validade de 10 minutos e autorização estrita via Policies (`OrderPolicy`).

### ⚙️ Painel Administrativo
- Dashboard administrativo com indicadores de faturamento, pedidos e contagem de itens ativos.
- CRUD completo de produtos com suporte a upload de capas (`public/covers`) e upload seguro de binários compactados.
- Visualização e rastreio de pedidos em modo leitura.

---

## 🏗️ Arquitetura & Boas Práticas

- **PSR-12 & PHP 8.4+:** Código padronizado e formatado via **Laravel Pint**.
- **Thin Controllers & Services:** Lógicas de pagamento, upload e geração de links isoladas em serviços dedicados:
  - `App\Services\PaymentService`
  - `App\Services\DownloadService`
  - `App\Services\ProductUploadService`
- **Form Requests:** Toda entrada de formulário e filtros validada estritamente via classes `FormRequest`:
  - `StoreProductRequest`, `UpdateProductRequest`, `CatalogFilterRequest`.
- **Policies:** Autorização declarativa via `ProductPolicy` e `OrderPolicy`.
- **Integridade Relacional:** Chaves estrangeiras protegidas com `foreignId()->constrained()->onDelete(...)` e regras de unicidade para slugs com suporte a `SoftDeletes`.

---

## 📋 Pré-requisitos

- **PHP 8.3+** (recomendado PHP 8.4) com extensões: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`.
- **Composer** (v2.x)
- **Node.js** (v20+ / v22+) & **npm**
- **MySQL 8.0+** (ou MySQL 8.4 LTS com InnoDB)

---

## 🚀 Instalação e Execução Local

### 1. Clonar e Acessar o Diretório
```bash
git clone https://github.com/rayhenrique/kltecnologia-store.git
cd kltecnologia-store
```

### 2. Instalar Dependências
```bash
composer install
npm install
```

### 3. Configurar Variáveis de Ambiente
Copie o arquivo de exemplo e gere a chave da aplicação:
```bash
copy .env.example .env
php artisan key:generate
```

Edite o arquivo `.env` com as configurações do seu banco de dados MySQL:
```dotenv
APP_NAME="KL Tecnologia"
APP_ENV=local
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=America/Fortaleza
APP_LOCALE=pt_BR

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kltecnologia
DB_USERNAME=root
DB_PASSWORD=
DB_ENGINE=InnoDB
```

### 4. Executar Migrações e Seeders
```bash
php artisan migrate --seed
```

> **Contas Pré-configuradas:**
> - **Administrador Principal:** `admin@example.com` | Senha: `[REMOVED-ADMIN-PASSWORD]`
> - **Administrador Dev:** `admin@kltecnologia.test` | Senha: `password`
> - **Cliente Demo:** `cliente@kltecnologia.test` | Senha: `password`
>
> *Para criar ou resetar o admin em qualquer ambiente via terminal:*
> ```bash
> php artisan app:create-admin admin@example.com [REMOVED-ADMIN-PASSWORD]
> ```

### 5. Compilar Assets e Iniciar o Servidor
Em terminais separados (ou via script integrado):
```bash
# Terminal 1: Servidor Web Laravel
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev
```

Acesse a aplicação em: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 💳 Integração Mercado Pago

Para habilitar pagamentos reais ou em modo sandbox, adicione ao seu `.env`:

```dotenv
MERCADO_PAGO_ACCESS_TOKEN=APP_USR-seu-token-aqui
MERCADO_PAGO_WEBHOOK_SECRET=seu-secret-de-webhook-aqui
MERCADO_PAGO_WEBHOOK_URL=https://seu-dominio-publico.com/webhooks/mercado-pago
MERCADO_PAGO_BASE_URL=https://api.mercadopago.com
```

---

## 🧪 Testes Automatizados

O projeto possui cobertura de testes automatizados (Feature & Unit) para todos os fluxos críticos da aplicação:

```bash
# Executar todos os testes
php artisan test

# Executar validação de código e formatação
vendor/bin/pint --test

# Build de produção do frontend
npm run build
```

**Resultado da suíte:**
```text
Tests:    65 passed (208 assertions)
Duration: ~4s
```

### Cenários cobertos:
- Autenticação, registro e bloqueio de atribuição indevida da role admin.
- Isolamento do painel administrativo por middleware e policies.
- Vitrine e listagem de produtos com filtros do catálogo (busca, preço, categoria, ordenação).
- Fluxo de checkout com gateway Mercado Pago.
- Webhook com assinatura criptográfica HMAC e idempotência.
- Segurança de download: bloqueio para não autenticados, pedidos não pagos, usuários não proprietários e verificação de assinatura temporária.
- Constraints de banco de dados e integridade referencial.

---

## 📁 Estrutura de Rotas Principais

| Método | Rota | Descrição | Middleware / Proteção |
|---|---|---|---|
| `GET` | `/` | Vitrine inicial com produtos em destaque | Público |
| `GET` | `/catalogo` | Catálogo dedicado com filtros laterais | Público |
| `GET` | `/produtos/{slug}` | Detalhes do produto | Público |
| `POST` | `/checkout/{product}` | Inicia pedido e redireciona para gateway | `auth` |
| `POST` | `/webhooks/mercado-pago` | Notificações de pagamento | Assinatura HMAC |
| `GET` | `/customer/downloads` | Lista de downloads do cliente | `auth`, `verified` |
| `GET` | `/customer/downloads/{order}` | Download seguro do arquivo | `auth`, `verified`, `signed`, `Policy` |
| `GET` | `/admin` | Dashboard administrativo | `auth`, `verified`, `admin` |
| `RESOURCE` | `/admin/products` | CRUD de produtos digitais | `auth`, `verified`, `admin` |
| `GET` | `/admin/orders` | Listagem de pedidos | `auth`, `verified`, `admin` |

---

## 📄 Governança e Especificações

- [PRD.md](PRD.md) — Documento de requisitos e objetivos de negócio.
- [DESIGN.md](DESIGN.md) — Diretrizes visuais, tokens e tipografia.
- [DATABASE-SCHEMA.md](DATABASE-SCHEMA.md) — Modelo entidade-relacionamento e dicionário de dados.
- [UX-CONTRACT.md](UX-CONTRACT.md) — Estados de interface, mensagens de feedback e fluxos de navegação.
- [TASKS.md](TASKS.md) — Rastreamento de progresso e fases concluídas.

---

## 👤 Autor

Desenvolvido por **Ray Henrique**  
- GitHub: [@rayhenrique](https://github.com/rayhenrique)
