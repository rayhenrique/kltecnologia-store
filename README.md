# KL Tecnologia Store

Plataforma de e-commerce moderna para venda e distribuição de produtos digitais (templates, scripts, sistemas e infoprodutos), desenvolvida com **Laravel 13**, **PHP 8.3+**, **MySQL 8**, **Tailwind CSS** e **Alpine.js**.

O sistema conta com fluxo completo de checkout (compra direta ou carrinho), integração com **Mercado Pago** (PIX e Cartão), suporte a produtos 100% gratuitos (Lead Magnets), cupons de desconto dinâmicos, entrega privada de arquivos com URLs assinadas, blog corporativo com categorias, newsletter com exportação CSV, e-mails transacionais e painel administrativo SaaS com tema escuro.

---

## 🚀 Principais Recursos

### 🛍️ Vitrine & Experiência do Cliente
- **Vitrine & Catálogo Completo:** Listagem com ordenação inteligente (produtos recentes/atualizados primeiro) e seção de itens em destaque com badge `HOT`.
- **Busca Global Rápida (Spotlight):** Modal interativo com atalho de teclado (`Ctrl+K` / `Cmd+K` / `ESC`), foco automático e sugestões em alta.
- **Carrinho de Compras Reativo (`/carrinho`):** Gerenciado via Alpine.js e `localStorage`, com atualização em tempo real, cálculo de descontos e sugestões de produtos relacionados.
- **Favoritos (`/favoritos`):** Sistema de favoritos com sincronização automática entre o navegador e o banco de dados e contadores reativos na barra superior.
- **Checkout Integrado (`/checkout`):** Fluxo unificado para visitantes e clientes autenticados. Criação de conta em uma única etapa, validação de CPF e telefone com máscaras e aceite obrigatório de termos.
- **Produtos Gratuitos (Lead Magnets):** Liberação imediata de download para produtos com preço zero ou cupons de 100% de desconto, sem acionar o gateway de pagamento.
- **Entrega Digital Segura (`/customer/downloads`):** Acesso à biblioteca do cliente ("Meus Downloads") com links de download privados protegidos por assinatura temporal (`Storage::download()`).
- **Blog Corporativo (`/blog`):** Artigos categorizados com renderização de conteúdo HTML sanitizado, cálculo dinâmico de tempo de leitura e sidebar com posts recomendados.
- **Privacidade & LGPD:** Banner flutuante de consentimento de cookies com modal de preferências granulares e páginas dedicadas de [Política de Privacidade](/politica-de-privacidade) e [Termos de Uso](/termos-de-uso).
- **Newsletter:** Inscrição assíncrona com feedback por toast e tratamento idempotente de leads.

### 🛡️ Painel Administrativo SaaS
- **Dashboard Central:** Visão consolidada de métricas de vendas, pedidos e catálogo.
- **Sidebar Colapsável:** Menu lateral moderno com rolagem suave, modo recolhido (apenas ícones) e persistência do estado no `localStorage`.
- **Gestão de Produtos (`/admin/products`):** CRUD completo, métricas em tempo real, busca full-text, filtros por status/destaque, upload de binários de até 512MB e conversão automática de capas para WebP.
- **Gestão de Pedidos (`/admin/orders`):** Acompanhamento de transações, filtros por status (`pending`, `paid`, `failed`, `canceled`), métricas financeiras e atualização manual de pedidos.
- **Gestão de Cupons (`/admin/coupons`):** Criação de cupons por percentual ou valor fixo, limite de usos, data de expiração, restrição por produto específico ou toda a loja, com cópia rápida do código.
- **Gestão de Categorias:** Organização em dois módulos independentes — Categorias de Produtos (`/admin/categories`) e Categorias do Blog (`/admin/blog-categories`).
- **Gestão de Artigos do Blog (`/admin/posts`):** Publicador com sanitização estrita de HTML, upload de capas otimizadas e contagem de visualizações.
- **Gestão de Leads da Newsletter (`/admin/newsletter`):** Listagem com métricas, botão para copiar e-mails da página e exportação em streaming para arquivo CSV compatível com Excel (UTF-8 com BOM).

### 📧 E-mails Transacionais
- **Boas-vindas (`WelcomeCustomerMail`):** Disparado automaticamente no primeiro cadastro do cliente.
- **Pedido Pendente (`OrderPendingMail`):** Instruções de pagamento e resumo da compra para pedidos aguardando compensação (PIX / Cartão).
- **Pagamento Confirmado (`OrderPaidMail`):** Notificação imediata com botão de direcionamento para "Meus Downloads" após aprovação do Mercado Pago, checkout gratuito ou atualização manual pelo administrador.

---

## 🏗️ Arquitetura e Padrões de Código

O projeto segue as convenções PSR-12, boas práticas do Laravel 13 e princípios de Clean Architecture:

- **Form Requests (`app/Http/Requests`):** Validação e sanitização estrita de 100% dos inputs de formulários e requisições assíncronas. Nenhuma regra de validação dispersa nas Controllers.
- **Policies de Autorização (`app/Policies`):** Controle rigoroso de acesso a recursos administrativos e downloads de pedidos.
- **Camada de Serviços (`app/Services`):**
  - `CheckoutService`: Orquestração transacional de criação de contas, pedidos, reserva de cupons e geração de preferências.
  - `MercadoPagoService`: Integração com a API de pagamentos do Mercado Pago com dados completos do pagador para antifraude.
  - `WebhookService`: Tratamento idempotente e seguro das notificações de pagamento via assinatura de webhook.
  - `ProductStorageService`: Gerenciamento do armazenamento privado de binários e processamento de capas.
  - `OrderMailService`: Centralização e envio resiliente de e-mails transacionais com tratamento de falhas.
  - `HtmlSanitizerService`: Sanitização de HTML com whitelist de tags para prevenção de XSS no blog.
- **Armazenamento Privado:** Binários digitais armazenados em `storage/app/digital_products` (fora do `public`), acessíveis somente via `Storage::download()` com Policy e verificação de pedido pago.

---

## 📋 Requisitos do Sistema

- **PHP:** 8.3 ou superior (extensões: `pdo_mysql`, `gd` ou `imagick`, `mbstring`, `curl`, `fileinfo`)
- **Composer:** 2.x
- **Node.js:** 20.x ou superior & NPM
- **Banco de Dados:** MySQL 8.0+ ou MariaDB 10.5+

---

## ⚙️ Instalação Local

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/rayhenrique/kltecnologia-store.git
   cd kltecnologia-store
   ```

2. **Instale as dependências PHP:**
   ```bash
   composer install
   ```

3. **Configure as variáveis de ambiente:**
   ```bash
   copy .env.example .env     # No Windows
   # ou: cp .env.example .env # No Linux/macOS
   php artisan key:generate
   ```

4. **Configure o banco de dados no `.env`:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kltecnologia
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

5. **Execute as migrações e seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Instale e compile os assets do frontend:**
   ```bash
   npm install
   npm run build
   # ou para desenvolvimento com live-reload:
   npm run dev
   ```

7. **Inicie o servidor local:**
   ```bash
   php artisan serve
   ```

---

## 👤 Criação do Administrador

Por segurança, a aplicação não possui administradores pré-cadastrados com senhas padrão. Crie o primeiro administrador via CLI:

```bash
php artisan app:create-admin admin@seu-dominio.com --name="Administrador"
```
> O comando solicitará a senha de forma oculta no terminal, exigindo no mínimo 8 caracteres com letras maiúsculas, minúsculas, números e símbolos.

Para automações ou pipelines de deploy, utilize a variável de ambiente `ADMIN_PASSWORD`:
```bash
ADMIN_PASSWORD="SuaSenhaForte123!" php artisan app:create-admin admin@seu-dominio.com --name="Administrador"
```

---

## 🛠️ Comandos Artisan Personalizados

| Comando | Descrição |
|---|---|
| `php artisan app:create-admin <email>` | Cria ou atualiza um usuário administrador com senha segura. |
| `php artisan app:scrape-plw` | Scraper do catálogo antigo para importação inicial de produtos e capas. |
| `php artisan app:scrape-plw-blog` | Scraper de artigos para importação inicial do blog com download de capas. |

---

## 🛣️ Rotas Principais

### Rotas Públicas (Storefront)
| Método | Rota | Nome | Descrição |
|---|---|---|---|
| `GET` | `/` | `storefront.index` | Vitrine inicial com produtos em destaque e lançamentos |
| `GET` | `/catalogo` | `catalog.index` | Catálogo de produtos com filtros e paginação |
| `GET` | `/produtos/{slug}` | `storefront.show` | Detalhes do produto, especificações e FAQ |
| `GET` | `/carrinho` | `cart.index` | Carrinho de compras interativo |
| `GET` | `/favoritos` | `favorites.index` | Lista de desejos sincronizada |
| `POST` | `/cupons/validar` | `coupons.validate` | Validação de cupom de desconto em tempo real |
| `POST` | `/newsletter` | `newsletter.subscribe` | Inscrição na newsletter da loja |
| `GET` | `/blog` | `blog.index` | Listagem de artigos do blog |
| `GET` | `/blog/{slug}` | `blog.show` | Leitura de artigo do blog |
| `GET` | `/politica-de-privacidade` | `privacy.index` | Página oficial de privacidade (LGPD) |
| `GET` | `/termos-de-uso` | `terms.index` | Termos e condições de uso |

### Checkout & Área do Cliente
| Método | Rota | Nome | Descrição |
|---|---|---|---|
| `GET` | `/checkout` | `checkout.index` | Página de identificação e finalização |
| `POST` | `/checkout/processar` | `checkout.process` | Processamento do pedido e gateway |
| `GET` | `/checkout/retorno/{status}` | `checkout.return` | Retorno do Mercado Pago (sucesso, pendente, falha) |
| `POST` | `/webhooks/mercado-pago` | `webhooks.mercado-pago` | Webhook assinado para confirmação de pagamento |
| `GET` | `/customer/downloads` | `customer.downloads` | Biblioteca de downloads e histórico de pedidos |
| `GET` | `/customer/downloads/{order}` | `customer.download` | Download seguro com URL assinada e Policy |

### Painel Administrativo (`/admin`)
| Método | Rota | Nome | Descrição |
|---|---|---|---|
| `GET` | `/admin` | `admin.dashboard` | Painel de controle e métricas |
| `RESOURCE`| `/admin/products` | `admin.products.*` | Gestão de produtos, preços e binários |
| `RESOURCE`| `/admin/categories` | `admin.categories.*` | Gestão de categorias da loja |
| `RESOURCE`| `/admin/orders` | `admin.orders.*` | Gestão e acompanhamento de pedidos |
| `RESOURCE`| `/admin/coupons` | `admin.coupons.*` | Gestão de cupons promocionais |
| `RESOURCE`| `/admin/posts` | `admin.posts.*` | Gestão de artigos do blog |
| `RESOURCE`| `/admin/blog-categories` | `admin.blog-categories.*` | Gestão de categorias do blog |
| `GET` | `/admin/newsletter` | `admin.newsletter.index` | Listagem e gestão de inscritos na newsletter |
| `GET` | `/admin/newsletter/export` | `admin.newsletter.export` | Exportação de leads em CSV com BOM |

---

## 🧪 Qualidade de Código e Testes

A aplicação conta com uma suíte de testes automatizados com cobertura para todos os fluxos críticos:

```bash
# Executar a suíte de testes (Feature & Unit)
php artisan test

# Verificar padronização de código PSR-12 com Laravel Pint
vendor/bin/pint --test

# Corrigir automaticamente estilo de código
vendor/bin/pint

# Auditorias de segurança
composer audit --locked
npm audit --audit-level=moderate
```

---

## 🌐 Deploy em Produção

O guia completo de deploy em VPS com **CloudPanel** (Nginx + PHP 8.4 + MySQL + SSL Let's Encrypt) está documentado em detalhes no arquivo [`DEPLOY.md`](DEPLOY.md).

### Checklist Pré-Lançamento:
- [ ] `APP_ENV=production` e `APP_DEBUG=false`
- [ ] Certificado SSL ativo (HTTPS forçado)
- [ ] Webhook do Mercado Pago configurado com chave secreta no painel do gateway
- [ ] Credenciais do Mercado Pago (`MERCADO_PAGO_ACCESS_TOKEN`, `MERCADO_PAGO_PUBLIC_KEY`) em produção
- [ ] Configuração de SMTP funcional para envio de e-mails transacionais
- [ ] Cron Job do Laravel ativo para o scheduler (`* * * * * php /caminho/artisan schedule:run >> /dev/null 2>&1`)
- [ ] Execução dos comandos de otimização de cache:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
  ```

---

## 📄 Licença

Este projeto é de propriedade da **KL Tecnologia**. Todos os direitos reservados.
