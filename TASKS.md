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

- [x] **Fase 8: Conformidade Mercado Pago & Cadastro do Cliente**
  - [x] Adicionar campos `cpf` e `phone` na tabela `users` via migration.
  - [x] Atualizar Model `User` com `$fillable` e accessors limpos (`clean_cpf`, `clean_phone`).
  - [x] Atualizar `RegisterRequest` e `ProfileUpdateRequest` com validações.
  - [x] Adicionar inputs no design dark SaaS de `register.blade.php` e no perfil com máscaras automáticas em JavaScript.
  - [x] Integrar dados completos do pagador (`name`, `surname`, `identification`, `phone`) no `MercadoPagoService::createPreference` para aprovação e antifraude sem atrito.
  - [x] Criar e executar testes automatizados cobrindo o novo fluxo de cadastro.

- [x] **Fase 9: Web Scraper & Importação do Catálogo PLW Design**
  - [x] Criar migration tornando `file_path` nullable na tabela `products` para suporte a upload posterior.
  - [x] Criar comando Artisan `app:scrape-plw` para varredura e importação paginada com parser DOM.
  - [x] Extrair títulos, descrições detalhadas, capas em alta resolução e preços comerciais de tabela (`<del>`).
  - [x] Baixar e salvar capas localmente em `public/covers/`.
  - [x] Ajustar painel admin com alerta visual de upload pendente e edição direta de arquivos.
  - [x] **Fase 10: Redesign e Estruturação da Página do Produto**
  - [x] Criar Page Header Banner com breadcrumbs, badges de status e tipografia de destaque.
  - [x] Implementar layout de 2 colunas com moldura de capa e box de acesso vitalício.
  - [x] Desenvolver sistema de abas interativas com Alpine.js (Descrição rica, Recursos/Requisitos técnicos e Avaliações com depoimentos verificados).
  - [x] Construir sidebar sticky com card de compra (preço riscado comparativo, botão Mercado Pago, botão direto de WhatsApp e checklist de segurança).
  - [x] Desenvolver card de Informações do Produto (categoria, atualização, licença vitalícia, entrega imediata, versão).
  - [x] Implementar seção de 5 Badges de Confiança ("Por que comprar na KL Tecnologia?").
  - [x] Implementar grade responsiva de 4 Produtos Relacionados ("Você pode gostar") com link direto.
  - [x] Desenvolver seção de Guias & Blog e Accordion interativo de Dúvidas Frequentes (FAQ).
  - [x] Escrever teste automatizado para página de detalhes do produto e validar 100% de aprovação na suíte PHPUnit e Pint.

- [x] **Fase 11: Módulo Blog, Web Scraper & Sidebar Admin**
  - [x] Criar migration para tabela `posts` (título, slug, categoria, resumo, conteúdo HTML, capa, status publicado, contador de views e data de publicação).
  - [x] Criar migration adicionando `category` e `version` na tabela `products`.
  - [x] Desenvolver comando Artisan `app:scrape-plw-blog` para web scraping de artigos do blog PLW Design com download local de capas em `public/blog_covers/`.
  - [x] Executar web scraper e importar 18 artigos completos para o banco de dados.
  - [x] Criar layout do Painel Administrativo com Sidebar lateral fixa escura (`x-admin-layout`), contadores dinâmicos e menu responsivo mobile.
  - [x] Criar CRUD completo de Artigos do Blog no Admin (`PostController`, `StorePostRequest`, `UpdatePostRequest`, `PostPolicy`, views `index`, `create`, `edit` e `_form`).
  - [x] Atualizar CRUD de Produtos do Admin alinhando campos de Categoria (datalist) e Versão do Sistema com a vitrine.
  - [x] Criar rotas públicas `/blog` e `/blog/{post:slug}` e views da loja (`blog.index` e `blog.show`) com compartilhamento social, sidebar sticky e artigos recomendados.
  - [x] Adicionar link "Blog" no navbar superior da vitrine e no rodapé.
  - [x] Escrever testes automatizados Feature para Blog público (`BlogTest`) e Admin (`AdminPostCrudTest`) com 100% de aprovação na suíte PHPUnit e conformidade Pint.

- [x] **Fase 12: Módulo de Categorias no Painel Admin**
  - [x] Criar migration para tabela `categories` (name, slug, description, icon, is_active) e chave estrangeira `category_id` na tabela `products`.
  - [x] Popular categorias padrão e vincular produtos existentes automaticamente.
  - [x] Criar Model `Category` com scopes, slug automático (`HasUniqueSlug`) e relacionamento `products()`.
  - [x] Criar `CategoryPolicy`, `StoreCategoryRequest` e `UpdateCategoryRequest` protegendo acesso e validação.
  - [x] Criar `CategoryController` (CRUD completo: index com contagem de produtos, create, edit, update, destroy).
  - [x] Inserir item "Categorias" no menu lateral do Admin (Sidebar) em "E-commerce & Catálogo" com contador dinâmico.
  - [x] Criar views administrativas de Categorias (`index`, `create`, `edit`, `_form`) no padrão dark SaaS.
  - [x] Integrar formulário de produtos (`admin.products._form`) com select de categorias cadastradas e link rápido para gestão.
  - [x] Criar suíte de testes `AdminCategoryTest` cobrindo permissões, CRUD e vínculo de produtos com 100% de aprovação (89 testes no total).


