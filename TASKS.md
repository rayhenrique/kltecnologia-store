# TASKS.md

- [x] **Fase 1: Setup e Infraestrutura Base**
  - [x] Inicializar projeto Laravel padrão.
  - [x] Configurar `.env` para conexão com MySQL.
  - [x] Instalar Laravel Breeze (Blade) e compilar assets.
  - [x] Criar migrations para alteração da tabela `users` (adicionar `role`).
  - [x] Criar migrations para `products` e `orders`.
  - [x] Executar migrations.

- [x] **Fase 2: Models e Relacionamentos**
  - [x] Configurar Model `User` com casts e scopes (is_admin).
  - [x] Configurar Model `Product` com Casts (price decimal) e Sluggable trait/mutator.
  - [x] Configurar Model `Order` com relacionamentos (`user`, `product`).
  - [x] Criar Factories e Seeders (1 Admin, 5 Products, 1 Customer para testes).

- [x] **Fase 3: Autorização e Rotas**
  - [x] Criar Middleware `EnsureUserIsAdmin`.
  - [x] Criar arquivos de rotas agrupados (`routes/web.php`): `/admin` (protegidas por admin), `/customer` (protegidas por auth), `/` (públicas).
  - [x] Configurar links condicionais no layout do Breeze (esconder menu Admin para Customers).

- [x] **Fase 4: Painel Administrativo**
  - [x] Criar `ProductController` (Admin) com métodos de listagem, criação, edição.
  - [x] Criar `StoreProductRequest` e `UpdateProductRequest` validando arquivos e imagens.
  - [x] Implementar lógica de upload: capas em `public/covers`, arquivos de venda em `storage/app/digital_products`.
  - [x] Criar `OrderController` (Admin) apenas para listagem em modo leitura (dashboard).

- [x] **Fase 5: Vitrine e Checkout**
  - [x] Criar `StorefrontController` para listar produtos ativos na home e exibir detalhes.
  - [x] Criar views públicas (Blade + Tailwind) para vitrine.
  - [x] Criar `CheckoutController` (exige Auth). Lógica: recebe ID do produto, cria `Order` como 'pending', faz requisição cURL/Guzzle para API do gateway (Mercado Pago) e redireciona para a URL de pagamento.

- [x] **Fase 6: Webhooks e Entrega de Arquivos**
  - [x] Criar `WebhookController` (rota isolada do CSRF em `bootstrap/app.php` ou `VerifyCsrfToken`).
  - [x] Implementar lógica no Webhook: buscar `Order` pelo reference, checar idempotência, atualizar para `paid`.
  - [x] Criar `CustomerController` para renderizar a view "Meus Downloads".
  - [x] Criar `DownloadController` que verifica se `auth()->id()` é dono do pedido e se está `paid`. Em caso positivo, retornar `Storage::download($product->file_path)`.

- [x] **Fase 7: Refinamento e Testes**
  - [x] Ajustar UI/UX de mensagens flash (sucesso, erro).
  - [x] Escrever testes básicos automatizados (Feature Tests) para: Bloqueio de download sem pagamento, alteração de status via webhook simulado e criação de produto por Admin.
