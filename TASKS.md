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
  - [x] Criar migration tornando `file_path` nullable na tabela `products` para suporte a upload posterior, mantendo o produto inativo até o envio do arquivo.
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

- [x] **Fase 13: Topbar Interativa & Modal de Busca Global**
  - [x] Adicionar ícones de Favoritos e Carrinho com contadores dinâmicos via `localStorage` e eventos Alpine.js na navbar (topbar).
  - [x] Substituir caixa de busca fixa por botão de ícone de lupa interativo na topbar.
  - [x] Implementar Modal de Busca global (Spotlight) com backdrop escuro com blur, atalhos de teclado (`Ctrl+K` / `Cmd+K` / `ESC`), foco automático no input e tags de termos em alta.

- [x] **Fase 14: Identidade Visual & Favicon Oficial**
  - [x] Integrar arquivo de logotipo oficial (`logo-kltecnologia.png`) nos diretórios públicos (`public/images/`, `public/favicon.ico`, `public/favicon.png`).
  - [x] Substituir badge de texto/svg "KL" pelo ícone oficial na navbar da vitrine, mantendo o texto "KL Tecnologia" na frente.
  - [x] Atualizar rodapé da loja, sidebar do painel administrativo, tela de login/cadastro (guest) e navegação do cliente com o ícone oficial.
  - [x] Configurar tags `<link rel="icon">` e `<link rel="apple-touch-icon">` em todos os layouts da aplicação.
- [x] **Fase 15: Auditoria e Responsividade Mobile-First**
  - [x] Implementar drawer off-canvas deslizante com menu mobile na vitrine (`layouts/storefront.blade.php`), incluindo atalhos para catálogo, blog, termos de busca e autenticação.
  - [x] Criar sanfona de filtros colapsável no catálogo (`catalog/index.blade.php`) para priorizar visualização dos produtos em telas menores.
  - [x] Implementar barra inferior fixa (sticky purchase bar) na página de detalhes do produto (`storefront/show.blade.php`) com âncora direta de checkout.
  - [x] Otimizar formulários de autenticação (`login.blade.php`, `register.blade.php`) com padding dinâmico para telas estreitas (360px–390px).
  - [x] Compactar header e botões de ação do Painel Administrativo (`layouts/admin.blade.php`, `categories/_form.blade.php`, `posts/_form.blade.php`) garantindo layout sem quebras em visualização mobile.
- [x] **Fase 16: Página de Carrinho de Compras (`/carrinho`)**
  - [x] Criar `CartController` e registrar rota pública `cart.index` (`/carrinho`).
  - [x] Desenvolver view `resources/views/cart/index.blade.php` com suporte reativo (`localStorage` + Alpine.js), cálculo em tempo real de subtotal, descontos, total e produtos recomendados.
  - [x] Implementar sistema de cupons promocionais com feedback instantâneo (ex: `VIP10`, `KL2026`).
  - [x] Adicionar botões de "Adicionar ao Carrinho" com notificação toast interativa na vitrine, catálogo e página de detalhes do produto.
  - [x] Atualizar links de carrinho na navbar (topbar) e drawer mobile para a nova rota oficial.
  - [x] Criar testes automatizados Feature (`CartTest`) com 100% de aprovação na suíte PHPUnit (92 testes, 323 asserções) e Pint.

- [x] **Fase 17: Sidebar Colapsável do Painel Admin (Scroll Ativo & Modo Somente Ícones)**
  - [x] Habilitar barra de rolagem vertical personalizada (`overflow-y-auto admin-sidebar-scroll`) para navegação fluida sem cortes em telas com menor altura.
  - [x] Implementar botão de alternância (toggle) no header da sidebar e na topbar do painel com transição suave e ícone de seta animado.
  - [x] Salvar estado de colapso no navegador via `localStorage` (`admin_sidebar_collapsed`) garantindo persistência entre páginas e recarregamentos.
  - [x] Configurar modo recolhido (apenas ícones): largura contrai para 5rem (`w-20`), textos/títulos/contadores são ocultados, ícones permanecem centralizados com tooltips nativos (`title`) e badges discretos.
  - [x] Configurar modo expandido: largura total de 16rem (`w-64`), textos da marca KL Tecnologia, versão, seções, contadores numéricos e perfil completo visíveis.
  - [x] Ajustar padding dinâmico da área principal de conteúdo (`admin-main-collapsed` e `admin-main-expanded`) garantindo transição sem sobreposições.
  - [x] Preservar drawer off-canvas mobile em telas menores (< 1024px) para não afetar usabilidade em smartphones e tablets.

- [x] **Fase 18: CRUD Completo de Pedidos no Painel Administrativo**
  - [x] Atualizar Model `Order` com `user_id` em `$fillable` e query scopes `scopeSearch` e `scopeStatus`.
  - [x] Expandir `OrderPolicy` com autorização administrativa para `view`, `create`, `update` e `delete`.
  - [x] Criar Form Requests `StoreOrderRequest` e `UpdateOrderRequest` com sanitização e validação de valores e enums.
  - [x] Registrar recurso completo de rotas `Route::resource('orders', AdminOrderController::class)` em `routes/web.php`.
  - [x] Implementar todos os métodos no `Admin\OrderController` (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`).
  - [x] Desenvolver views do painel: `index` (com 4 cards de métricas, busca textual, filtro de status e ações), `create`, `edit`, `_form` compartilhado e `show` (com dados do produto, cliente, financeiro e alteração rápida de status).
  - [x] Criar suíte de testes Feature `AdminOrderCrudTest` com 8 testes cobrindo todas as operações e validações.
  - [x] Validar 100% de aprovação na suíte geral do PHPUnit (100 testes, 386 asserções) e conformidade no Pint.

- [x] **Fase 19: Módulo de Categorias do Blog no Painel Administrativo ("Conteúdo & Blog")**
  - [x] Criar migration para tabela `blog_categories` (name, slug, description, icon, is_active) e chave estrangeira `blog_category_id` na tabela `posts`.
  - [x] Popular categorias padrão e vincular os 18 artigos existentes automaticamente sem perda de dados.
  - [x] Criar Model `BlogCategory` com scopes `active`, `search`, trait `HasUniqueSlug` e relacionamento `posts()`.
  - [x] Atualizar Model `Post` com relacionamento `blogCategory()` e `$fillable` atualizado.
  - [x] Criar `BlogCategoryPolicy`, `StoreBlogCategoryRequest` e `UpdateBlogCategoryRequest` protegendo rotas com perfil admin.
  - [x] Criar `Admin\BlogCategoryController` (CRUD completo com contagem de artigos vinculados `withCount('posts')`).
  - [x] Inserir item "Categorias" no menu lateral (Sidebar) sob "Conteúdo & Blog" com contador dinâmico e suporte a modo colapsado.
  - [x] Desenvolver views administrativas de Categorias do Blog (`index`, `create`, `edit`, `_form`) no padrão dark SaaS.
  - [x] Integrar formulário de artigos (`admin/posts/_form.blade.php`) com select de categorias cadastradas e link rápido de gestão.
  - [x] Criar suíte de testes `AdminBlogCategoryTest` com 7 testes aprovados (107 testes no total, 420 asserções) e Pint 100% validado.

- [x] **Fase 20: Redesign da Página de Perfil (`/profile`)**
  - [x] Criar Hero Profile Banner moderno escuro (`bg-slate-950` com luz ambiente teal/azul), avatar dinâmico com iniciais estilizadas, badges de perfil (`Admin Master` / `Cliente VIP`) e verificação de e-mail.
  - [x] Redesenhar formulário de Dados Pessoais & Faturamento com inputs modernos, suporte a CPF (Mercado Pago), WhatsApp e máscaras reativas em tempo real.
  - [x] Redesenhar formulário de Segurança & Alteração de Senha com botões de alternância de visibilidade (olho), checklist de requisitos e feedback visual.
  - [x] Criar coluna lateral com Resumo de Status da Conta (total de pedidos, downloads liberados, CPF/WhatsApp vinculados e atalho para biblioteca).
  - [x] Adicionar card de Privacidade & Proteção (Criptografia SSL 256-bit, LGPD, antifraude Mercado Pago).
  - [x] Reformular seção de Zona de Risco e Modal de Exclusão de Conta com avisos claros em português e proteção por senha.
  - [x] Garantir 100% de responsividade mobile-first e aprovação total nos testes automatizados (`ProfileTest`, `PasswordUpdateTest` e suíte geral de 107 testes).

- [x] **Fase 21: Fluxo de Compra E-Commerce & Checkout com Cadastro Integrado**
  - [x] Criar rota pública e página de checkout dedicada `GET /checkout` (`checkout.index`) com suporte a compra direta (`?product=slug`) e carrinho do navegador.
  - [x] Desenvolver formulário de checkout dark SaaS com identificação e criação de conta automática para visitantes (Nome, E-mail, CPF, WhatsApp, Senha com confirmação e alternância de visibilidade).
  - [x] Suportar atualização e confirmação de dados para clientes já autenticados sem fricção.
  - [x] Criar `ProcessCheckoutRequest` com validações robustas de conta, produtos disponíveis e cupons cadastrados no painel.
  - [x] Aprimorar `CheckoutService::process` para cadastrar visitante, efetuar login automático com evento `Registered`, instanciar pedidos e gerar preferência no Mercado Pago.
  - [x] Expandir `MercadoPagoService` e `WebhookService` para suportar pedidos individuais e múltiplos com conciliação idempotente de pagamentos.
  - [x] Atualizar botão "Comprar" na página do produto (`storefront.show`) e na barra fixa mobile para direcionar diretamente ao checkout sem barreiras de login prévio.
  - [x] Atualizar botão de finalização no carrinho de compras (`cart.index`) para link unificado de checkout.
  - [x] Reformular página "Meus Downloads & Pedidos" (`customer.downloads`) para exibir status em tempo real de pedidos pagos (com link assinado) e pedidos pendentes (com aviso de confirmação).
  - [x] Expandir suíte de testes Feature (`CheckoutTest` e `CartTest`) alcançando 100% de aprovação (114 testes, 452 asserções) e conformidade estrita no Pint.

- [x] **Fase 22: Produtos Gratuitos (Lead Magnet) & Liberação Direta sem Mercado Pago**
  - [x] Ajustar validação de criação e edição de produtos no painel Admin (`StoreProductRequest` e `UpdateProductRequest`) permitindo preço zero (`min:0`).
  - [x] Ajustar validação do formulário de checkout (`ProcessCheckoutRequest`) com detecção de pedido gratuito (`isFreeOrder()`), tornando CPF e telefone opcionais e preservando cadastro simples de leads (Nome, E-mail, Senha).
  - [x] Atualizar `CheckoutService::start` e `CheckoutService::process` para pedidos com valor zero (`$totalAmount <= 0`) ou cupons cadastrados com 100% de desconto:
    - Cria pedidos com status imediato `OrderStatus::Paid`.
    - Registra método de pagamento como `free` (`payment_method = 'free'`).
    - Ignora completamente a chamada ao Mercado Pago (evita erro de valor mínimo do gateway).
    - Retorna URL direta para biblioteca do cliente (`customer.downloads`).
  - [x] Atualizar `CheckoutController` para redirecionar internamente pedidos gratuitos com mensagem de boas-vindas e sucesso.
  - [x] Atualizar interface da página de checkout (`checkout/index.blade.php`):
    - Ocultar métodos de pagamento do Mercado Pago quando o total for R$ 0,00.
    - Exibir banner explicativo "Pedido 100% Gratuito — Nenhuma cobrança será realizada".
    - Alterar texto do botão de ação principal para "Liberar Download Grátis".
  - [x] Atualizar vitrine e catálogo (`storefront/show.blade.php`, `storefront/index.blade.php`, `catalog/index.blade.php` e `cart/index.blade.php`):
    - Exibir badges e etiquetas "100% Grátis" / "GRÁTIS".
    - Trocar botão de "Comprar Agora" para "Baixar Grátis" nos produtos com preço zero.
    - Adaptar barra de compra sticky no mobile para itens gratuitos.
  - [x] Criar testes automatizados para visitantes, clientes autenticados e cupons de 100% sem acionar o Mercado Pago (`CheckoutTest` e `AdminProductTest`), alcançando 118 testes aprovados (477 asserções) e 100% de conformidade no Laravel Pint.

- [x] **Fase 23: Página de Favoritos (`/favoritos`) & Gestão Reativa no E-Commerce**
  - [x] Criar `FavoriteController` com métodos `index()` (vitrine e recomendados) e `items()` (sincronização de produtos salvos no navegador com dados frescos do banco).
  - [x] Criar rotas web `GET /favoritos` (`favorites.index`) e `POST /favoritos/items` (`favorites.items`).
  - [x] Desenvolver a página completa de Favoritos `resources/views/favorites/index.blade.php`:
    - Layout SaaS escuro com visualização em grid responsivo de produtos favoritados.
    - Sincronização automática entre `localStorage` e banco de dados via Alpine.js.
    - Estado vazio estilizado com ícone ilustrativo e CTA para explorar catálogo.
    - Ações rápidas em cada card: remover favorito, adicionar ao carrinho `[+]`, compra direta e download gratuito.
    - Botão "Limpar Lista" com confirmação.
    - Carrossel / grid de "Mais Produtos em Destaque" recomendados.
  - [x] Integrar botões de coração (favoritar) em todo o ecossistema:
    - Navbar superior desktop (`topbar-favorites-link`) com link direto e badge dinâmico de contagem.
    - Menu drawer mobile com atalho "Meus Favoritos" e contador reativo.
    - Vitrine inicial (`storefront.index` em Destaques e Lançamentos).
    - Catálogo de produtos (`catalog.index`).
    - Página de detalhes do produto (`storefront.show` tanto na imagem de capa quanto na caixa de compra).
  - [x] Fornecer helpers globais `window.toggleFavorite(product)` e `window.isFavorite(id)` com despacho de eventos reativos (`favorites-updated`, `toast-message`).
  - [x] Criar suíte de testes `FavoriteTest` com 100% de aprovação (5 testes, 23 asserções) cobrindo renderização, recomendados, listagem por IDs, tratamento de IDs vazios e links de navegação.
  - [x] Validar conformidade total do Laravel Pint e execução dos 123 testes da aplicação.

- [x] **Fase 24: Banner de Cookies LGPD & Central de Privacidade e Termos**
  - [x] Desenvolver componente Blade de consentimento de cookies (`resources/views/components/cookie-consent.blade.php`) em estrita conformidade com a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018):
    - Banner flutuante moderno dark SaaS com animações suaves (`x-transition`).
    - Opções claras de ação: "Aceitar Todos", "Apenas Essenciais" e "Personalizar Preferências".
    - Modal interativo de preferências dividindo cookies em 4 categorias (Essenciais/Obrigatórios, Preferências/Favoritos, Desempenho/Analíticos e Comunicação/Marketing).
    - Persistência das escolhas no `localStorage` (`kl_cookie_consent`) e disparo de eventos reativos (`cookie-consent-updated`).
    - Possibilidade de reabertura das configurações a qualquer momento via evento global `open-cookie-settings`.
  - [x] Desenvolver `LegalController` com rotas públicas dedicadas:
    - `GET /politica-de-privacidade` (`privacy.index`): Documento completo com identificação do controlador, dados coletados, bases legais (Art. 7º), tabela técnica de cookies, medidas de segurança (SSL 256-bit, hashes, signed URLs), direitos do titular (Art. 18) e canal direto do DPO/Encarregado.
    - `GET /termos-de-uso` (`terms.index`): Regras de licenciamento comercial definitivo, entrega digital imediata, suporte técnico e garantias.
  - [x] Atualizar rodapé do layout da loja (`resources/views/layouts/storefront.blade.php`):
    - Links diretos para Termos de Uso e Política de Privacidade.
    - Botão interativo para abrir o gerenciador de cookies da LGPD em qualquer página.
  - [x] Suportar dinamicamente tanto `<x-storefront-layout>` quanto `@extends('layouts.storefront')` com fallback seguro `$slot ?? ''` e `@yield('content')`.
  - [x] Criar suíte de testes `LegalAndCookieConsentTest` com 100% de aprovação (4 testes, 25 asserções), elevando a suíte total para 127 testes aprovados (525 asserções).

- [x] **Fase 25: Aceite de Termos de Uso & Política de Privacidade no Checkout**
  - [x] Atualizar o formulário de checkout (`resources/views/checkout/index.blade.php`):
    - Checkbox estilizado com links clicáveis que abrem os Termos de Uso (`terms.index`) e a Política de Privacidade (`privacy.index`) em nova aba (`target="_blank"`).
    - Integração bidirecional com Alpine.js (`acceptedTerms`) e suporte a re-preenchimento via `old('terms')`.
    - Bloqueio reativo do botão de submissão enquanto os termos não forem aceitos.
    - Exibição de mensagens de erro de validação sob o campo.
  - [x] Adicionar regra de validação obrigatória no backend (`app/Http/Requests/ProcessCheckoutRequest.php`):
    - Regra `'terms' => ['accepted']` para garantir que o cliente concorde ativamente antes de gerar o pedido.
    - Mensagem em português amigável: *"Você precisa ler e concordar com os Termos de Uso e a Política de Privacidade para finalizar o pedido."*
  - [x] Expandir suíte de testes automatizados (`tests/Feature/CheckoutTest.php`):
    - Teste de obrigatoriedade do aceite dos termos (`test_checkout_validation_requires_terms_acceptance`).
    - Teste de renderização dos links legais no checkout (`test_checkout_page_renders_terms_and_privacy_links`).
    - Atualização dos fluxos de compras de visitantes, clientes e cupons com aceite de termos, totalizando 129 testes aprovados (534 asserções).
  - [x] 100% de conformidade com o Laravel Pint.

- [x] **Fase 26: Módulo Completo de Cupons de Desconto no Painel Administrativo & Checkout Dinâmico**
  - [x] Criar migration `2026_09_13_150000_create_coupons_table.php` e tabela `coupons`:
    - Campos: `code`, `description`, `discount_type` (percentage/fixed), `discount_value`, `min_order_amount`, `product_id` (nullable com foreign key e onDelete null), `max_uses` (nullable), `times_used` (contador), `starts_at` (temporário agendado), `expires_at` (data de expiração), `is_active` (boolean), soft deletes, timestamps e índices de busca.
  - [x] Desenvolver Model `Coupon` (`app/Models/Coupon.php`):
    - Relacionamento `belongsTo(Product::class)`.
    - Sanitização automática com código em caixa alta (`strtoupper`) ao salvar.
    - Métodos auxiliares: `isExpired()`, `hasStarted()`, `hasReachedLimit()`, `isApplicableToStorewide()`, `incrementUsage()`.
    - Método de inteligência de negócio `evaluate(Collection $products, float $subtotal)` que valida se o cupom está ativo, no prazo, dentro do limite, atinge valor mínimo e é elegível aos itens do pedido, calculando o desconto preciso.
  - [x] Criar Policy `CouponPolicy` (`app/Policies/CouponPolicy.php`) restringindo visualização, criação, edição e exclusão a administradores (`$user->isAdmin()`).
  - [x] Criar Form Requests com validações completas:
    - `StoreCouponRequest`: Código único, validação de percentual (máx 100%), datas válidas (`after_or_equal`), limite numérico de utilizações e tipo de desconto (`percentage` ou `fixed`).
    - `UpdateCouponRequest`: Validação única ignorando o registro atual, mensagens amigáveis em português.
  - [x] Criar `Admin\CouponController` (`app/Http/Controllers/Admin/CouponController.php`):
    - CRUD completo com listagem paginada, filtros por status e busca textual por código ou descrição.
    - Métricas em cards no topo (total de cupons, ativos, utilizações acumuladas, expirados).
    - `store()`, `edit()`, `update()` e `destroy()` com exclusão segura e mensagens flash de feedback.
  - [x] Desenvolver Views Blade administrativas com design moderno SaaS (`resources/views/admin/coupons/`):
    - `index.blade.php`: Tabela responsiva com badges de status pulsantes, atalho de cópia do código para área de transferência em 1 clique, tipo de desconto destacado, escopo (Geral / Produto Específico) e paginação.
    - `create.blade.php`: Formulário dinâmico com Alpine.js alternando escopo (Toda a Loja vs Produto Específico) e tipo de desconto (% vs R$).
    - `edit.blade.php`: Formulário de edição com alerta de utilizações já realizadas.
  - [x] Integrar link de Cupons no menu lateral administrativo (`resources/views/layouts/partials/admin-sidebar.blade.php`) com badge de contagem em tempo real.
  - [x] Desenvolver endpoint de validação em tempo real `POST /cupons/validar` (`CouponValidationController`):
    - Validação de código contra o banco com checagem de regras em tempo real (data, limite, produto e valor mínimo).
    - Validação exclusiva de cupons persistidos no banco, sem códigos promocionais embutidos no código.
  - [x] Integrar validação e cálculo dinâmico de desconto:
    - `app/Services/CheckoutService.php`: Avaliação e aplicação de cupons dinâmicos aos itens do pedido, suporte a cupons de produto ou gerais (% ou fixo) e incremento automático de `times_used`.
    - `resources/views/checkout/index.blade.php`: Validação assíncrona via `fetch('/cupons/validar')` com feedback visual de carregamento, cálculo automático de desconto e submissão com o pedido.
    - `resources/views/cart/index.blade.php`: Validação assíncrona via `fetch('/cupons/validar')` no carrinho.
  - [x] Desenvolver suítes completas de testes automatizados:
    - `tests/Feature/AdminCouponTest.php` (9 testes, 42 asserções) cobrindo controle de acesso (visitante, cliente, admin), CRUD completo e regras de validação.
    - `tests/Feature/CouponValidationTest.php` (10 testes, 24 asserções) cobrindo o endpoint `/cupons/validar`, expiração, limite, agendamento futuro, produto específico e aplicação real no checkout com incremento de uso.
  - [x] Suíte geral elevada para **148 testes aprovados (600 asserções)** com 100% de conformidade no Laravel Pint.

- [x] **Fase 27: Produtos em Destaque na Home & Ordenação Recente no Catálogo Completo**
  - [x] Criar migration `2026_09_13_160000_add_is_featured_to_products_table.php` adicionando coluna indexada `is_featured` (boolean, default false) na tabela `products`.
  - [x] Atualizar Model `Product` (`app/Models/Product.php`):
    - Adicionar `is_featured` em `$fillable`, `$attributes` (false) e `$casts` (boolean).
    - Criar escopo local `scopeFeatured($query)`.
    - Atualizar `ProductFactory` com estado `featured()`.
  - [x] Atualizar módulo administrativo de cadastro/edição de produtos:
    - Form Requests (`StoreProductRequest` e `UpdateProductRequest`): regra `'is_featured' => ['nullable', 'boolean']` e merge em `prepareForValidation()`.
    - Formulário (`admin/products/_form.blade.php`): campo interativo com destaque visual e badge "★ HOT" para marcar produtos como destaque na home.
    - Listagem (`admin/products/index.blade.php`): badge "★ Destaque" no card de status do produto.
  - [x] Atualizar vitrine da página inicial (`StorefrontController.php`):
    - Seção "Produtos em Destaque" (`$featuredProducts`): consulta filtrada por `where('is_featured', true)` priorizando os produtos marcados pelo admin no painel (com fallback seguro para itens recentes).
    - Seção "Catálogo Completo" (`$products`): ordenação automática por `orderByDesc('updated_at')->orderByDesc('created_at')` garantindo que produtos criados recentemente ou atualizados recentemente apareçam sempre primeiro.
  - [x] Atualizar página de catálogo completo (`CatalogController.php`):
    - Ordenação padrão configurada para priorizar produtos criados ou atualizados recentemente (`orderByDesc('updated_at')->orderByDesc('created_at')`).
  - [x] Criar e atualizar testes automatizados:
    - `AdminProductTest`: criação e edição de produtos com flag `is_featured`.
    - `StorefrontTest`: verificação da seção de destaques e ordenação do catálogo completo com `travelTo()`.
    - `CatalogTest`: verificação de ordenação cronológica com produtos atualizados recentemente no topo.
  - [x] Suíte de testes geral elevada para **153 testes aprovados (616 asserções)** com 100% de conformidade no Laravel Pint.

- [x] **Fase 28: Módulo de Newsletter e Exportação de Leads no Painel Admin**
  - [x] Criar migration `2026_09_13_170000_create_newsletter_subscribers_table.php` criando a tabela `newsletter_subscribers` com colunas `email` (único, indexado), `ip_address`, `user_agent`, `is_active`, `subscribed_at`, `unsubscribed_at`, `created_at`, `updated_at` e `deleted_at` (soft deletes).
  - [x] Criar Model `NewsletterSubscriber` (`app/Models/NewsletterSubscriber.php`):
    - `$fillable`, `$attributes`, `$casts`, normalização automática para minúsculo em `booted()`, `scopeActive()` e suporte a `SoftDeletes`.
  - [x] Criar Policy `NewsletterSubscriberPolicy` (`app/Policies/NewsletterSubscriberPolicy.php`):
    - Restrição de `viewAny`, `view`, `delete` e `export` exclusivamente para administradores (`$user->isAdmin()`).
  - [x] Criar Form Request `StoreNewsletterSubscriberRequest` (`app/Http/Requests/StoreNewsletterSubscriberRequest.php`):
    - Validação de e-mail obrigatório, formato válido e normalização em `prepareForValidation()`.
  - [x] Criar Controller público de inscrição `NewsletterSubscriptionController` (`app/Http/Controllers/NewsletterSubscriptionController.php`):
    - Tratamento idempotente de novos cadastros e reativação de descadastrados ou registros excluídos.
    - Suporte a requisições JSON assíncronas (fetch/AJAX) e submissões tradicionais de formulário com flash message.
  - [x] Conectar o formulário de Newsletter da vitrine inicial (`resources/views/layouts/storefront.blade.php`):
    - Componente interativo Alpine.js com envio assíncrono via `fetch` para `route('newsletter.subscribe')`.
    - Token CSRF dinâmico, animação de loading spinner, feedback visual de sucesso e erro, e disparo do evento global de toast.
  - [x] Criar módulo administrativo completo de Newsletter (`/admin/newsletter`):
    - Controller `Admin\NewsletterSubscriberController`:
      - `index()`: 4 cards de métricas (Total de Inscritos, Leads Ativos, Novos este Mês, Novos Hoje), busca textual por e-mail, filtro por status (Todos, Ativos, Inativos) e paginação com preservação de querystring.
      - `export()`: Download em streaming de arquivo CSV (`newsletter-inscritos-YYYY-MM-DD.csv`) com delimitador ponto e vírgula, codificação UTF-8 com BOM (`\xEF\xBB\xBF`) garantindo compatibilidade imediata com Microsoft Excel e Google Sheets, processamento em chunks de 500 registros para alta escalabilidade.
      - `destroy()`: Exclusão com confirmação e soft delete.
    - View `resources/views/admin/newsletter/index.blade.php`:
      - Interface visual dark/light minimalista SaaS com Alpine.js.
      - Botão de exportação direta para CSV.
      - Botão "Copiar E-mails da Página" para área de transferência em 1 clique.
      - Botão de cópia individual ao passar o mouse sobre qualquer e-mail da listagem.
      - Estado vazio acolhedor quando nenhum lead for encontrado.
    - Menu lateral (`resources/views/layouts/partials/admin-sidebar.blade.php`):
      - Novo item "Newsletter" com ícone dedicado e contador de leads cadastrados em tempo real.
  - [x] Desenvolver suítes completas de testes automatizados:
    - `tests/Feature/NewsletterTest.php` (7 testes) cobrindo inscrição via form, requisições JSON, e-mails em caixa alta, idempotência, reativação de inativos/deletados e validações.
    - `tests/Feature/AdminNewsletterTest.php` (7 testes) cobrindo proteção de rotas contra visitantes e clientes comuns, visualização de métricas e listagem, busca por e-mail, filtro por status, exportação CSV com validação de BOM e headers, e exclusão de leads.
  - [x] Suíte de testes geral elevada para **167 testes aprovados (667 asserções)** com 100% de conformidade no Laravel Pint.

- [x] **Fase 29: E-mails Transacionais de Notificação ao Cliente (Boas-Vindas, Pendência e Confirmação de Compra)**
  - [x] Criar classes Mailables no padrão Laravel 13.x (`app/Mail/`):
    - `WelcomeCustomerMail`: E-mail de boas-vindas com confirmação de ativação de conta, dados de acesso e orientações de segurança.
    - `OrderPendingMail`: E-mail de pedido recebido aguardando compensação de pagamento (PIX, Boleto, Cartão), lista de produtos e instruções de compensação.
    - `OrderPaidMail`: E-mail de compra confirmada e pagamento aprovado com botão destacado para "Baixar Meus Produtos Agora" e instruções de uso.
  - [x] Desenvolver templates responsivos de e-mail com layout dark/light moderno SaaS da KL Tecnologia (`resources/views/emails/`):
    - `emails.layouts.default`: Cabeçalho escuro `#040812` com logotipo em teal `#14b8a6`, container centralizado de 600px, tipografia limpa e rodapé com informações da loja e suporte.
    - `emails.welcome-customer`: Card de boas-vindas, e-mail de acesso e botão de direcionamento para o painel.
    - `emails.order-pending`: Tabela com itens do pedido, valores em R$, orientações para PIX/Cartão/Boleto e link para acompanhar.
    - `emails.order-paid`: Badge verde de pagamento aprovado, resumo dos produtos adquiridos e botão direto para download dos arquivos.
  - [x] Criar serviço dedicado `OrderMailService` (`app/Services/OrderMailService.php`):
    - Métodos `sendWelcomeEmail()`, `sendOrderPendingEmail()` e `sendOrderPaidEmail()`.
    - Normalização automática de pedidos individuais ou múltiplos (carrinho de compras).
    - Tratamento resiliente a falhas com `try/catch` e logs detalhados, garantindo que oscilações de SMTP nunca travem o checkout ou webhooks.
  - [x] Integrar disparos automáticos nos pontos estratégicos do ciclo de vida de pedidos:
    - `CheckoutService::process` e `start`: detecção inteligente de primeiro pedido/cadastro para envio de boas-vindas (`WelcomeCustomerMail`), envio de pendência para pedidos com valor pendente (`OrderPendingMail`) e envio de pagamento aprovado imediato para pedidos 100% gratuitos (`OrderPaidMail`).
    - `WebhookService::handlePayment`: disparo de `OrderPaidMail` assim que o Mercado Pago confirma status `approved` (com proteção contra reenvio em webhooks repetidos).
    - `Admin\OrderController::update`: disparo de `OrderPaidMail` caso o administrador atualize manualmente o status de um pedido para `Paid`.
  - [x] Desenvolver suíte completa de testes automatizados (`tests/Feature/OrderEmailTest.php` com 7 testes e 36 asserções):
    - Boas-vindas e pendência no checkout de novo cliente.
    - Boas-vindas e confirmação imediata em checkout gratuito.
    - Não reenvio de boas-vindas para clientes antigos com compras anteriores.
    - Confirmação de compra via Webhook do Mercado Pago.
    - Idempotência para webhooks duplicados.
    - Confirmação manual de pedido no painel administrativo.
    - Renderização sem erros de todos os templates HTML de e-mail.
  - [x] Suíte de testes geral elevada para **174 testes aprovados (703 asserções)** com 100% de conformidade no Laravel Pint.

- [x] **Fase 30: Campo de Busca e Filtros no Módulo de Produtos do Painel Administrativo**
  - [x] Atualizar Model `Product` (`app/Models/Product.php`):
    - Implementar query scope `scopeSearch($query, ?string $term)` pesquisando por ID exato, título, slug, categoria, descrição e versão.
  - [x] Aprimorar `Admin\ProductController` (`app/Http/Controllers/Admin/ProductController.php`):
    - Receber `ListFilterRequest $request` no método `index()`.
    - Suportar termos de busca enviados via parâmetros `search` ou `q`.
    - Suportar filtro por status de ativação (`all`, `active`, `inactive`).
    - Suportar filtro por produtos em destaque na vitrine (`all`, `featured`).
    - Calcular estatísticas em tempo real (`metrics`): total de produtos, ativos, ocultos/inativos e destaques da home.
    - Preservar parâmetros de busca na paginação através de `withQueryString()`.
    - Enviar parâmetros de filtro e métricas para a view.
  - [x] Modernizar a view administrativa de produtos (`resources/views/admin/products/index.blade.php`):
    - Adicionar 4 cards de métricas no padrão SaaS dark/light (Total de Produtos, Produtos Ativos, Ocultos/Inativos e Destaques na Home).
    - Adicionar barra de busca estilizada com ícone de lupa, dropdown de status e dropdown de destaque com submissão automática e botão Filtrar.
    - Adicionar botão "Limpar" quando qualquer filtro ou termo de busca estiver ativo.
    - Aprimorar listagem exibindo badge de categoria quando preenchido e indicador de integridade do arquivo digital.
    - Criar empty state contextual e acolhedor diferenciando catálogo vazio de busca sem resultados com botão "Limpar Busca e Filtros".
  - [x] Expandir suíte de testes de produtos administrativos (`tests/Feature/AdminProductTest.php`):
    - Teste de visualização da listagem com métricas corretas.
    - Teste de busca por título e por slug (`search` e `q`).
    - Teste de filtro por status (`active` e `inactive`).
    - Teste de filtro por produtos em destaque (`featured`).
  - [x] Suíte de testes geral elevada para **178 testes aprovados (723 asserções)** com 100% de conformidade no Laravel Pint.

- [x] **Fase 31: Hardening de Segurança, Checkout e Governança**
  - [x] Remover credenciais administrativas padrão do código e da documentação.
  - [x] Exigir senha forte por prompt oculto ou variável de ambiente no comando de administrador.
  - [x] Bloquear catálogo e checkout de produtos sem arquivo digital.
  - [x] Desativar registros sem arquivo por migration e manter novos imports inativos.
  - [x] Remover cupons promocionais embutidos no código.
  - [x] Tornar reserva e liberação de uso de cupom transacionais e idempotentes.
  - [x] Sanitizar HTML de artigos administrativos, importados e já armazenados.
  - [x] Impedir senha de checkout na sessão e evitar cadastro parcial para produto indisponível.
  - [x] Migrar validações diretas identificadas para Form Requests.
  - [x] Atualizar README, esquema relacional e guia de deploy.
  - [x] Adicionar CI com testes, Pint, build, auditorias e validação MySQL.
  - [x] Adicionar cobertura automatizada para as correções de segurança.
  - [x] Suíte geral elevada para **184 testes aprovados (747 asserções)**.

- [x] **Fase 32: Otimização de Performance WebP, Barra de Upload e Resolução Automática de Capas**
  - [x] Otimizar logos e 77 capas de produtos para formato `.webp` de alta performance.
  - [x] Adicionar conversão automática para `.webp` e redimensionamento proporcional no upload de novas capas.
  - [x] Aumentar limites de upload para 512MB e suporte a arquivos `.tar` e `.gz`.
  - [x] Implementar barra de progresso em tempo real com validação prévia de tamanho no painel administrativo.
  - [x] Implementar accessors automáticos `cover_path` em `Product` e `Post` com fallback seguro para `.webp`.
  - [x] Criar migration de sincronização para atualizar referências de capas legadas para `.webp`.
  - [x] Corrigir escape de atributos Blade na vitrine (`{!! ... !!}`).
  - [x] Suíte de testes geral elevada para **186 testes aprovados (757 asserções)** com 100% de aprovação no Laravel Pint.

- [x] **Fase 33: Acessibilidade e Contraste de Cores WCAG AA/AAA (Lighthouse 100/100)**
  - [x] Otimizar `.btn-teal` para `bg-teal-700` com hover em `bg-teal-800` garantindo taxa de contraste de 5.47:1 (acima do mínimo de 4.5:1 exigido pelo WCAG AA).
  - [x] Otimizar `.badge-new` para `bg-teal-400` com texto `text-slate-950` elevando a taxa de contraste para 10.84:1 (padrão WCAG AAA).
  - [x] Atualizar botões de compra e links de detalhes no catálogo e vitrine de `bg-teal-600` para `bg-teal-700/800`.
  - [x] Ajustar textos de rodapé no container inferior de `text-slate-500` para `text-slate-400` (taxa de 7.87:1 no fundo `slate-950`).
  - [x] Ajustar botões do modal de consentimento de cookies e botões CTA de produtos gratuitos para `emerald-700/800`.
  - [x] Suíte de testes geral aprovada com **187 testes (757 asserções)** e 100% de conformidade com o Laravel Pint.

- [x] **Fase 34: Infraestrutura Técnica de SEO, Sitemap Dinâmico e Schema.org JSON-LD**
  - [x] Criar `SitemapController` e rota pública `GET /sitemap.xml` com header `Content-Type: application/xml`.
  - [x] Desenvolver template `resources/views/sitemap.blade.php` com protocolo Sitemaps.org (<loc>, <lastmod>, <changefreq>, <priority>) agregando páginas institucionais, categorias ativas, produtos válidos para venda e posts publicados.
  - [x] Atualizar `public/robots.txt` bloqueando rotas utilitárias/privadas (`/admin/`, `/checkout`, `/carrinho`, `/favoritos`, `/customer/`, `/login`, `/register`) e apontando `Sitemap: https://kltecnologia.com/sitemap.xml`.
  - [x] Atualizar layout `resources/views/layouts/storefront.blade.php` com meta tags dinâmicas, canonical dinâmico, Twitter cards e JSON-LD global (`Organization` e `WebSite` com `SearchAction`).
  - [x] Injetar dados estruturados Schema.org (JSON-LD) para `Product` (com offers em BRL e InStock) e `BreadcrumbList` na página de produto (`storefront/show.blade.php`).
  - [x] Injetar dados estruturados Schema.org (JSON-LD) para `Article` e `BreadcrumbList` na página de artigo do blog (`blog/show.blade.php`).
  - [x] Desenvolver suíte de testes Feature `SeoAndSitemapTest` cobrindo o XML do sitemap, regras do robots.txt e integridade dos schemas JSON-LD.
  - [x] Suíte de testes geral elevada para **193 testes aprovados (798 asserções)** com 100% de conformidade com o Laravel Pint.

- [x] **Fase 35: Notificação por E-mail ao Administrador em Novas Compras Realizadas**
  - [x] Configurar `ADMIN_NOTIFICATION_EMAIL=rayhenrique@gmail.com` em `.env`, `.env.example` e chave `'admin_email'` em `config/mail.php`.
  - [x] Desenvolver classe Mailable `AdminNewOrderMail` (`app/Mail/AdminNewOrderMail.php`) com assunto dinâmico informativo: `🎉 [Nova Venda] Pedido #... - R$ ... - {Nome}`.
  - [x] Criar template responsivo de e-mail `resources/views/emails/admin-new-order.blade.php` no padrão SaaS dark/light da KL Tecnologia com:
    - Badge `🎉 Nova Compra Realizada!`.
    - Resumo dos dados do cliente (Nome, E-mail, CPF e link direto para WhatsApp com `https://wa.me/...`).
    - Tabela completa de itens adquiridos, valores unitários, método de pagamento e valor total.
    - Botão CTA destacado `Ver Pedido no Painel Admin →` com link direto para o pedido.
  - [x] Integrar método `sendAdminOrderPaidEmail` no `OrderMailService`:
    - Disparo automático em todas as compras confirmadas (webhook do Mercado Pago aprovado, checkout gratuito e aprovação manual de pedido no painel).
    - Resiliência com `try/catch` e logs detalhados, protegendo fluxos críticos de pagamento contra oscilações de SMTP.
  - [x] Atualizar suíte de testes Feature `OrderEmailTest` com validação de disparo do e-mail do admin para webhook aprovado, checkout gratuito, aprovação manual e renderização do template.
  - [x] Suíte de testes geral mantida com **193 testes aprovados (808 asserções)** e 100% de conformidade com o Laravel Pint.

- [x] **Fase 36: Módulo Nativo de Métricas de Tráfego & Visitas no Painel Admin**
  - [x] Criar migration `2026_09_15_120000_create_page_views_table.php` e tabela `page_views` indexada com suporte a hash anônimo (LGPD), dispositivos, rotas e relacionamento polimórfico com produtos e artigos do blog.
  - [x] Criar Model `PageView` (`app/Models/PageView.php`) com escopos temporais (`today`, `yesterday`, `thisMonth`, `lastDays`).
  - [x] Criar Middleware `TrackPageViews` (`app/Http/Middleware/TrackPageViews.php`) com execução assíncrona (`dispatchAfterResponse`) para latência zero, ignorando bots, rotas utilitárias e acessos de administradores.
  - [x] Registrar middleware globalmente no grupo `web` em `bootstrap/app.php`.
  - [x] Criar serviço `TrafficAnalyticsService` (`app/Services/TrafficAnalyticsService.php`) com consolidação de métricas:
    - Visitas hoje e comparação com ontem.
    - Visitantes únicos no mês e total de visualizações.
    - Taxa de conversão da loja em tempo real (`pedidos pagos ÷ visitantes únicos`).
    - Proporção de dispositivos (Mobile vs Desktop).
    - Gráfico cronológico interativo de 14 dias com alturas proporcionais.
    - Ranking dos Top 5 Produtos mais acessados nos últimos 30 dias.
    - Ranking dos Top 5 Artigos mais lidos no blog.
  - [x] Atualizar `DashboardController` (`app/Http/Controllers/Admin/DashboardController.php`) e template `resources/views/admin/dashboard.blade.php` com interface dark/light SaaS completa e responsiva.
  - [x] Desenvolver suíte de testes automatizados `TrafficAnalyticsTest` (`tests/Feature/TrafficAnalyticsTest.php`).
  - [x] Suíte geral elevada para **200 testes aprovados (832 asserções)** com 100% de conformidade com o Laravel Pint.

- [x] **Fase 37: Inscrição Automática na Newsletter no Checkout, Termos de Uso e Disparo em Fila com Limite Diário (100/dia)**
  - [x] Criar migration `2026_09_15_170000_create_newsletter_send_logs_table.php` e Model `NewsletterSendLog` para controle diário de envios por e-mail e produto/artigo.
  - [x] Criar configuração `config/newsletter.php` com cota diária de 100 envios (`NEWSLETTER_DAILY_LIMIT=100`) e intervalo de segurança entre mensagens.
  - [x] Criar `NewsletterService` com métodos para subscrição automática de compradores, geração de tokens seguros HMAC para cancelamento de inscrição, verificação de limite diário e idempotência de envio.
  - [x] Integrar subscrição automática no `CheckoutService` (para compras pagas e downloads gratuitos) e no `WebhookService` (na aprovação assíncrona do Mercado Pago).
  - [x] Adicionar Cláusula 6 ("Comunicações, Atualizações de Produtos e Inscrição na Newsletter") nos Termos de Uso (`resources/views/legal/terms.blade.php`), informando sobre a inscrição automática com garantia de opt-out (descadastro em 1 clique).
  - [x] Criar `NewsletterUnsubscribeController`, rota `GET /newsletter/cancelar-inscricao` e view de confirmação `resources/views/newsletter/unsubscribed.blade.php`.
  - [x] Adicionar link de descadastro seguro no rodapé do layout base de e-mails (`resources/views/emails/layouts/default.blade.php`).
  - [x] Criar Mailables `NewProductNewsletterMail` e `NewPostNewsletterMail` e templates responsivos Blade correspondentes.
  - [x] Criar Jobs de fila `SendNewProductNewsletterJob` e `SendNewPostNewsletterJob` com verificação de limite diário (quando atinge 100/dia, adia automaticamente via `$this->release()` para o dia seguinte às 00:05, mantendo a fila processando continuamente até zerar).
  - [x] Criar `NewsletterBroadcastService` enfileirando notificações escalonadas para todos os inscritos ativos quando um novo produto ou artigo for publicado.
  - [x] Integrar triggers de notificação no `Admin\ProductController` e `Admin\PostController`.
  - [x] Configurar worker periódico no agendador do Laravel em `routes/console.php` (`queue:work --stop-when-empty --max-time=50`).
  - [x] Desenvolver suíte completa de testes Feature `NewsletterAutomationTest` (`tests/Feature/NewsletterAutomationTest.php`) cobrindo checkout, opt-out, jobs, fila e limite de 100/dia.
  - [x] Suíte de testes geral elevada para **210 testes aprovados (856 asserções)** com 100% de conformidade com o Laravel Pint.

- [x] **Fase 38: Módulo Completo de Gestão de Clientes no Painel Admin**
  - [x] Criar `UserPolicy` (`app/Policies/UserPolicy.php`) com regras de autorização para `viewAny`, `view`, `create`, `update` e `delete` (bloqueando autoexclusão).
  - [x] Criar Form Requests `StoreCustomerRequest` e `UpdateCustomerRequest` com validação de nome, e-mail único, CPF, telefone, perfil e senha.
  - [x] Criar `CustomerController` (`app/Http/Controllers/Admin/CustomerController.php`) gerenciando CRUD completo, métricas em tempo real (Total de Clientes, Compradores Ativos, Novos no Mês e Faturamento LTV), filtros por status/tipo e ordenação dinâmica.
  - [x] Registrar recurso `customers` em `routes/web.php` no grupo administrativo (`admin.customers.*`).
  - [x] Desenvolver views Blade no padrão SaaS dark/light:
    - `index.blade.php`: Listagem de clientes com cards de métricas, busca global, filtros, avatares, links para WhatsApp e status da newsletter.
    - `show.blade.php`: Perfil do cliente, resumo de KPIs (LTV, ticket médio, total de pedidos), dados cadastrais/LGPD e histórico completo de compras.
    - `create.blade.php` e `edit.blade.php`: Cadastro manual e edição de dados com formulário compartilhado `_form.blade.php`.
  - [x] Integrar item "Clientes" na sidebar administrativa (`resources/views/layouts/partials/admin-sidebar.blade.php`) com ícone e contador dinâmico em tempo real.
  - [x] Adicionar método helper `isPaid()` no Model `Order`.
  - [x] Desenvolver suíte de testes Feature `AdminCustomerTest` (`tests/Feature/AdminCustomerTest.php`) com 11 testes cobrindo permissões, busca, filtros, CRUD e regras de integridade contábil na exclusão.
  - [x] Suíte de testes geral elevada para **221 testes aprovados (903 asserções)** com 100% de conformidade com o Laravel Pint.

- [x] **Fase 39: CRUD Completo do Módulo de Newsletter no Painel Admin**
  - [x] Atualizar `NewsletterSubscriberPolicy` (`app/Policies/NewsletterSubscriberPolicy.php`) com métodos `create` e `update`.
  - [x] Criar Form Requests `StoreNewsletterSubscriberRequest` e `UpdateNewsletterSubscriberRequest` com validações de e-mail e regras de unicidade seguras.
  - [x] Atualizar Model `NewsletterSubscriber` (`app/Models/NewsletterSubscriber.php`) com relacionamentos `sendLogs()` e `user()`, e accessor `unsubscribe_url`.
  - [x] Expandir `Admin\NewsletterSubscriberController` (`app/Http/Controllers/Admin/NewsletterSubscriberController.php`) com métodos `index`, `create`, `store`, `show`, `edit`, `update`, `toggleStatus`, `destroy` e `export`.
  - [x] Registrar rotas do recurso `newsletter` e endpoint rápido `newsletter.toggle-status` em `routes/web.php`.
  - [x] Desenvolver views Blade no padrão SaaS dark/light:
    - `index.blade.php`: Listagem com métricas, busca, filtro por status, botão "+ Novo Inscrito", e ações de Ver Detalhes, Editar, Toggle de Status e Excluir.
    - `_form.blade.php`: Formulário compartilhado com e-mail, status, data de inscrição e aviso explicativo sobre LGPD e anti-spam.
    - `create.blade.php`: Interface para cadastro manual de novos inscritos.
    - `edit.blade.php`: Interface para edição de e-mail e status.
    - `show.blade.php`: Painel detalhado do lead com metadados técnicos, vínculo com cliente cadastrado na loja, link assinado de cancelamento (opt-out) e histórico completo de notificações de novos produtos/posts recebidas.
  - [x] Desenvolver suíte de testes Feature `AdminNewsletterTest` (`tests/Feature/AdminNewsletterTest.php`) com 12 testes cobrindo permissões, listagem, filtros, CRUD completo, restauração de soft deletes, histórico de envios e exportação CSV.
  - [x] Suíte de testes geral elevada para **228 testes aprovados (968 asserções)** com 100% de conformidade com o Laravel Pint.
- [x] **Fase 40: Controle de Versões, Changelog e Modal de Novidades com Segmentação de Público**
  - [x] Criar migration `2026_09_15_220000_add_last_seen_version_to_users_table.php` adicionando coluna `last_seen_version` (`string`, nullable) na tabela `users`.
  - [x] Atualizar Model `User` (`app/Models/User.php`) com `last_seen_version` em `$fillable` e helper `hasSeenVersion(string $version): bool`.
  - [x] Criar arquivo de configuração `config/changelog.php` com versionamento semântico (`current_version => 2.1.0`), histórico de releases e segmentação de público (`admin`, `customer`, `all`).
  - [x] Criar documento de histórico `VERSOES.md` na raiz do projeto documentando todas as versões e fases desde a v1.0.0 até a v2.1.0.
  - [x] Criar camada de serviço `ChangelogService` (`app/Services/ChangelogService.php`) com métodos `getCurrentVersion`, `getLatestVersionForUser`, `getUnseenReleaseForUser`, `getAllReleasesForUser` e `dismissForUser`.
  - [x] Criar `ChangelogController` (`app/Http/Controllers/ChangelogController.php`) e registrar rotas autenticadas `POST /changelog/dismiss` e `GET /changelog/historico` em `routes/web.php`.
  - [x] Desenvolver componente Blade moderno Dark SaaS `<x-changelog-modal />` (`resources/views/components/changelog-modal.blade.php`) com Alpine.js, detecção de versão não vista, tabs para novidades e histórico de releases, e persistência assíncrona.
  - [x] Integrar modal nos layouts do sistema:
    - Painel Administrativo (`layouts/admin.blade.php`).
    - Sidebar do Admin (`layouts/partials/admin-sidebar.blade.php`): substituição de versão estática por botão dinâmico clicável `v{{ config('changelog.current_version') }}` disparando `open-changelog`.
    - Área do Cliente (`layouts/app.blade.php` em Meus Downloads e Perfil).
    - Vitrine e Loja (`layouts/storefront.blade.php` quando autenticado).
  - [x] Criar tela administrativa completa de Novidades (`/admin/novidades` - `Admin\ChangelogController@index` e `resources/views/admin/changelog/index.blade.php`) com métricas em tempo real, timeline visual interativa com todas as 6 versões, badges de público e pré-visualização do modal.
  - [x] Inserir item de menu dedicado "Novidades" na Sidebar administrativa (`resources/views/layouts/partials/admin-sidebar.blade.php`) com ícone estilizado, badge de versão dinâmico e suporte a modo recolhido/expandido.
  - [x] Desenvolver suíte completa de testes automatizados `ChangelogTest` (`tests/Feature/ChangelogTest.php`) com 12 testes e 39 asserções cobrindo autenticação, isolamento de notas de admin/cliente, dispensa assíncrona, json de histórico, tela de novidades do admin e item de menu na sidebar.
  - [x] Suíte de testes geral elevada para **240 testes aprovados (1007 asserções)** com 100% de conformidade com o Laravel Pint.

# SEO — KL Tecnologia

> Objetivo: preparar a infraestrutura técnica e editorial da KL Tecnologia para SEO orgânico antes da otimização individual dos produtos.
>
> **Regra de execução:** marcar uma tarefa como `[x]` somente quando a implementação correspondente estiver concluída e validada. Não marcar tarefas apenas porque o código foi iniciado.
>
> **Importante:** este plano não inclui a reescrita individual dos produtos existentes. O conteúdo SEO de cada produto será tratado posteriormente.

---

## Fase SEO 01 — Auditoria da implementação atual

- [x] Revisar a arquitetura SEO atual do projeto.
- [x] Revisar `routes/web.php`.
- [x] Revisar `StorefrontController`.
- [x] Revisar `CatalogController`.
- [x] Revisar `BlogController`.
- [x] Revisar `SitemapController`.
- [x] Revisar model `Product`.
- [x] Revisar model `Category`.
- [x] Revisar model `Post`.
- [x] Revisar model `BlogCategory`.
- [x] Revisar `Admin/ProductController`.
- [x] Revisar requests relacionados a produtos, posts e categorias.
- [x] Revisar `ProductStorageService`.
- [x] Revisar `resources/views/layouts/storefront.blade.php`.
- [x] Revisar `resources/views/storefront/index.blade.php`.
- [x] Revisar `resources/views/storefront/show.blade.php`.
- [x] Revisar `resources/views/catalog/index.blade.php`.
- [x] Revisar `resources/views/blog/index.blade.php`.
- [x] Revisar `resources/views/blog/show.blade.php`.
- [x] Revisar formulários administrativos.
- [x] Revisar migrations existentes.
- [x] Revisar `tests/Feature/SeoAndSitemapTest.php`.
- [x] Revisar `public/robots.txt`.
- [x] Identificar regressões ou inconsistências entre rotas, sitemap, canonicals e filtros.
- [x] Registrar no resumo final os problemas encontrados antes das alterações.

---

## Fase SEO 02 — Estrutura de dados dos produtos

- [x] Criar migration retrocompatível para novos campos SEO e comerciais de produtos.
- [x] Adicionar `short_description`.
- [x] Adicionar `seo_title`.
- [x] Adicionar `meta_description`.
- [x] Adicionar `product_type`.
- [x] Adicionar campo opcional para marca/desenvolvedor.
- [x] Adicionar campo para funcionalidades/recursos.
- [x] Adicionar campo para requisitos.
- [x] Adicionar campo para informações de licença.
- [x] Adicionar campo para informações de suporte.
- [x] Adicionar `demo_url`.
- [x] Adicionar `documentation_url`.
- [x] Adicionar `includes_source_code`.
- [x] Adicionar `lifetime_access`.
- [x] Atualizar `$fillable` do model `Product`.
- [x] Atualizar casts necessários.
- [x] Garantir que todos os novos campos sejam opcionais quando aplicável.
- [x] Não criar `meta_keywords`.
- [x] Não alterar automaticamente slugs existentes.
- [x] Garantir compatibilidade com produtos já cadastrados.

---

## Fase SEO 03 — Administração de produtos

- [x] Criar seção `SEO & Apresentação` no formulário administrativo.
- [x] Adicionar campo `Título SEO`.
- [x] Adicionar campo `Meta description`.
- [x] Adicionar campo `Descrição curta`.
- [x] Adicionar campo `Tipo de produto`.
- [x] Adicionar campo `Marca / Desenvolvedor`.
- [x] Adicionar campo `Recursos / Funcionalidades`.
- [x] Adicionar campo `Requisitos`.
- [x] Adicionar campo `Licença`.
- [x] Adicionar campo `Suporte`.
- [x] Adicionar campo `URL de demonstração`.
- [x] Adicionar campo `URL de documentação`.
- [x] Adicionar opção `Contém código-fonte`.
- [x] Adicionar opção `Acesso vitalício`.
- [x] Implementar validação server-side.
- [x] Implementar contador visual para título SEO.
- [x] Implementar contador visual para meta description.
- [x] Implementar preview simples de resultado do Google.
- [x] Garantir que informações comerciais não sejam preenchidas automaticamente.

---

## Fase SEO 04 — Remover informações genéricas incorretas

- [x] Remover afirmação global `Código Fonte Incluso`.
- [x] Remover afirmação global `Uso Vitalício`.
- [x] Remover afirmação global `Licença Comercial Definitiva`.
- [x] Remover afirmação global `Código 100% desbloqueado`.
- [x] Remover afirmação global de banco de dados SQL.
- [x] Remover requisitos globais de PHP.
- [x] Remover requisitos globais de MySQL/MariaDB.
- [x] Remover requisitos globais de Apache/Nginx.
- [x] Remover afirmações globais sobre trava de domínio.
- [x] Remover afirmações globais sobre instalação para clientes.
- [x] Exibir código-fonte somente quando `includes_source_code = true`.
- [x] Exibir acesso vitalício somente quando `lifetime_access = true`.
- [x] Exibir requisitos somente quando cadastrados.
- [x] Exibir licença somente quando cadastrada.
- [x] Exibir suporte somente quando cadastrado.
- [x] Não apresentar a KL como desenvolvedora de produtos de terceiros.

---

## Fase SEO 05 — Avaliações e prova social

- [x] Remover o texto estático `48 avaliações de clientes verificados`.
- [x] Remover o texto estático `100% dos compradores avaliaram como excelente`.
- [x] Remover depoimentos genéricos repetidos entre produtos.
- [x] Não gerar avaliações fictícias.
- [x] Não gerar `aggregateRating` sem avaliações reais.
- [x] Não gerar `Review` estruturado sem avaliações reais.
- [x] Substituir a aba atual por `Licença, Suporte & Entrega` enquanto não existir sistema real de reviews.
- [x] Garantir que toda prova social exibida venha de dados reais.

---

## Fase SEO 06 — Página individual de produto

- [x] Tornar a página do produto completamente dinâmica.
- [x] Usar `seo_title` quando preenchido.
- [x] Implementar fallback seguro de título.
- [x] Não acrescentar automaticamente `Código Fonte` ao título.
- [x] Usar `meta_description` quando preenchida.
- [x] Usar `short_description` como primeiro fallback.
- [x] Usar trecho limpo de `description` como segundo fallback.
- [x] Manter exatamente um H1.
- [x] Usar título real do produto como H1.
- [x] Exibir descrição curta próxima ao topo.
- [x] Exibir descrição completa.
- [x] Exibir recursos somente quando existentes.
- [x] Exibir requisitos somente quando existentes.
- [x] Exibir licença somente quando existente.
- [x] Exibir suporte somente quando existente.
- [x] Exibir demonstração somente quando houver URL válida.
- [x] Exibir documentação somente quando houver URL válida.
- [x] Não deixar seções vazias.
- [x] Não inventar atributos do produto.

---

## Fase SEO 07 — Breadcrumbs dos produtos

- [x] Remover breadcrumb fixo `Scripts & SaaS`.
- [x] Usar categoria real do produto.
- [x] Implementar estrutura:
  - [x] Início.
  - [x] Catálogo.
  - [x] Categoria.
  - [x] Produto.
- [x] Fazer a categoria apontar para sua landing page SEO.
- [x] Atualizar `BreadcrumbList` JSON-LD com as mesmas URLs.
- [x] Garantir que URLs do breadcrumb sejam canônicas.

---

## Fase SEO 08 — Produtos relacionados

- [x] Remover `inRandomOrder()` como principal estratégia.
- [x] Priorizar produtos da mesma categoria.
- [x] Excluir o próprio produto.
- [x] Usar produtos recentes/relevantes como fallback.
- [x] Limitar quantidade de relacionados.
- [x] Garantir que somente produtos disponíveis para venda sejam exibidos.

---

## Fase SEO 09 — Conteúdo relacionado do blog

- [x] Remover artigos fixos sobre PHP/MySQL da página de produto.
- [x] Relacionar artigos dinamicamente.
- [x] Priorizar categoria/tópico relacionado.
- [x] Usar artigos recentes somente como fallback.
- [x] Não exibir seção caso não existam artigos razoavelmente relacionados.

---

## Fase SEO 10 — Home page

- [x] Revisar proposta de valor da Home.
- [x] Remover afirmações que indiquem que todos os produtos são autorais.
- [x] Posicionar a KL como loja de scripts, sistemas, templates e produtos digitais.
- [x] Revisar H1 principal.
- [x] Evitar keyword stuffing.
- [x] Evitar descrição completa dos produtos nos cards.
- [x] Usar `short_description` nos cards.
- [x] Criar fallback curto a partir de `description`.
- [x] Limitar tamanho do texto server-side.
- [x] Criar links visíveis para categorias principais.
- [x] Manter links para produtos estratégicos.
- [x] Manter acesso ao blog.

---

## Fase SEO 11 — Categorias de produtos com URLs limpas

- [x] Corrigir inconsistência atual entre `categoria` e `category`.
- [x] Criar rota de categoria com URL limpa.
- [x] Utilizar formato `/catalogo/{category:slug}`.
- [x] Criar rota nomeada `catalog.category` ou equivalente.
- [x] Usar Route Model Binding.
- [x] Filtrar produtos por `category_id`.
- [x] Não determinar categoria pesquisando palavras em título/descrição.
- [x] Preservar `/catalogo` como catálogo geral.
- [x] Criar canonical individual para cada categoria.
- [x] Criar H1 individual para cada categoria.
- [x] Mostrar descrição da categoria.
- [x] Mostrar somente produtos daquela categoria.
- [x] Adicionar breadcrumbs.
- [x] Garantir indexação da landing page.

---

## Fase SEO 12 — SEO das categorias de produto

- [x] Adicionar `seo_title` às categorias.
- [x] Adicionar `meta_description` às categorias.
- [x] Atualizar model `Category`.
- [x] Atualizar admin de categorias.
- [x] Adicionar validação.
- [x] Criar fallback para SEO title.
- [x] Criar fallback para meta description.
- [x] Manter `description` como conteúdo editorial da página.

---

## Fase SEO 13 — Categorias do blog com URLs limpas

- [x] Criar rota `/blog/categoria/{blogCategory:slug}`.
- [x] Usar Route Model Binding.
- [x] Atualizar links das categorias do blog.
- [x] Atualizar breadcrumbs.
- [x] Atualizar canonical.
- [x] Atualizar sitemap.
- [x] Evitar usar `?categoria=` como URL principal indexável.
- [x] Preservar compatibilidade com URLs antigas.
- [x] Criar 301 quando a URL antiga tiver substituição direta.

---

## Fase SEO 14 — SEO de categorias do blog

- [x] Adicionar `seo_title`.
- [x] Adicionar `meta_description`.
- [x] Atualizar model `BlogCategory`.
- [x] Atualizar admin.
- [x] Criar fallbacks seguros.
- [x] Garantir H1 único.
- [x] Exibir descrição editorial quando existente.

---

## Fase SEO 15 — Busca, filtros e facetas

- [x] Identificar URLs com `?q=`.
- [x] Identificar URLs com `?sort=`.
- [x] Identificar URLs com `?min_price=`.
- [x] Identificar URLs com `?max_price=`.
- [x] Identificar combinações de filtros.
- [x] Aplicar `noindex, follow` a páginas de busca.
- [x] Aplicar `noindex, follow` a páginas de ordenação.
- [x] Aplicar `noindex, follow` a filtros por faixa de preço.
- [x] Aplicar `noindex, follow` às combinações de facetas.
- [x] Não depender somente de `robots.txt` para evitar indexação.
- [x] Não bloquear via robots URLs que precisam ser rastreadas para que o `noindex` seja visto.

---

## Fase SEO 16 — Canonicals

- [x] Revisar uso atual de `url()->current()`.
- [x] Criar canonical explícito para Home.
- [x] Criar canonical explícito para catálogo.
- [x] Criar canonical explícito para categorias.
- [x] Criar canonical explícito para produtos.
- [x] Criar canonical explícito para blog.
- [x] Criar canonical explícito para categorias do blog.
- [x] Criar canonical explícito para posts.
- [x] Tratar corretamente URLs com filtros.
- [x] Tratar corretamente paginação.
- [x] Não canonicalizar página 2 automaticamente para página 1.
- [x] Evitar canonical contraditório com sitemap ou links internos.

---

## Fase SEO 17 — Layout global de metadados

- [x] Refatorar cuidadosamente `storefront.blade.php`.
- [x] Suportar `title`.
- [x] Suportar `metaDescription`.
- [x] Suportar `canonical`.
- [x] Suportar `robots`.
- [x] Suportar `ogTitle`.
- [x] Suportar `ogDescription`.
- [x] Suportar `ogImage`.
- [x] Suportar `ogType`.
- [x] Preservar Google Site Verification.
- [x] Preservar Google Analytics.
- [x] Preservar favicons.
- [x] Preservar Open Graph.
- [x] Preservar Twitter Card.
- [x] Não duplicar meta tags.
- [x] Não gerar meta description vazia.
- [x] Escapar corretamente conteúdo dinâmico.

---

## Fase SEO 18 — Schema global

- [x] Manter `Organization`.
- [x] Manter `WebSite`.
- [x] Remover `SearchAction` obsoleto.
- [x] Usar IDs consistentes para Organization/WebSite.
- [x] Não inventar telefone.
- [x] Não inventar endereço.
- [x] Não inventar perfis sociais.
- [x] Não inventar informações institucionais.

---

## Fase SEO 19 — Product JSON-LD

- [x] Preservar JSON-LD server-side no HTML inicial.
- [x] Manter `Product`.
- [x] Manter `Offer`.
- [x] Gerar `name`.
- [x] Gerar descrição verdadeira.
- [x] Gerar imagem somente quando existir.
- [x] Gerar SKU.
- [x] Gerar categoria quando existir.
- [x] Gerar marca somente quando conhecida.
- [x] Não usar KL Tecnologia como `brand` automaticamente.
- [x] Manter KL Tecnologia como `seller` quando apropriado.
- [x] Gerar `priceCurrency = BRL`.
- [x] Gerar preço real.
- [x] Gerar disponibilidade real.
- [x] Gerar item condition quando apropriado.
- [x] Remover `priceValidUntil = now()+1 ano`.
- [x] Só utilizar `priceValidUntil` quando existir data real.
- [x] Não gerar review falso.
- [x] Não gerar aggregateRating falso.
- [x] Não gerar imagem inválida quando `cover_path` for nulo.

---

## Fase SEO 20 — SEO do blog/post

- [x] Criar migration retrocompatível para `seo_title`.
- [x] Criar migration retrocompatível para `meta_description`.
- [x] Atualizar model `Post`.
- [x] Atualizar request/validation.
- [x] Atualizar formulário administrativo.
- [x] Adicionar seção `SEO`.
- [x] Criar contador de título.
- [x] Criar contador de meta description.
- [x] Criar preview simples de SERP.
- [x] Usar `seo_title` quando preenchido.
- [x] Usar `title` como fallback.
- [x] Usar `meta_description` quando preenchida.
- [x] Usar `excerpt` como primeiro fallback.
- [x] Usar trecho limpo do conteúdo como segundo fallback.
- [x] Não criar meta keywords.

---

## Fase SEO 21 — Article JSON-LD

- [x] Manter schema `Article`.
- [x] Manter `headline`.
- [x] Usar descrição correta.
- [x] Adicionar imagem somente quando existir.
- [x] Manter `datePublished`.
- [x] Manter `dateModified`.
- [x] Manter `author`.
- [x] Manter `publisher`.
- [x] Manter `mainEntityOfPage`.
- [x] Manter BreadcrumbList.
- [x] Não gerar URL de imagem inválida.

---

## Fase SEO 22 — Redirects de slugs

- [x] Preservar slug quando somente o título for alterado.
- [x] Não regenerar automaticamente slugs existentes.
- [x] Criar mecanismo de histórico de slugs/redirects.
- [x] Criar redirect 301 para slug antigo de produto.
- [x] Criar redirect 301 para slug antigo de post.
- [x] Criar redirect 301 para slug antigo de categoria.
- [x] Criar redirect 301 para slug antigo de categoria do blog.
- [x] Evitar cadeias de redirects.
- [x] Fazer slug antigo apontar diretamente para o slug atual.
- [x] Não redirecionar 404 genérico para Home.
- [x] Manter 404 quando a URL nunca existiu.

---

## Fase SEO 23 — Sitemap XML

- [x] Preservar endpoint `/sitemap.xml`.
- [x] Garantir `Content-Type: application/xml`.
- [x] Incluir Home.
- [x] Incluir catálogo.
- [x] Incluir categorias de produtos com URLs limpas.
- [x] Incluir produtos disponíveis.
- [x] Incluir blog.
- [x] Incluir categorias do blog com URLs limpas.
- [x] Incluir artigos publicados.
- [x] Incluir páginas institucionais relevantes.
- [x] Excluir pesquisas.
- [x] Excluir filtros.
- [x] Excluir ordenação.
- [x] Excluir carrinho.
- [x] Excluir favoritos.
- [x] Excluir checkout.
- [x] Excluir login/register.
- [x] Excluir admin.
- [x] Excluir customer.
- [x] Excluir drafts.
- [x] Excluir produtos inativos.
- [x] Excluir produtos indisponíveis.
- [x] Excluir categorias sem produtos disponíveis.
- [x] Excluir categorias do blog sem posts publicados.
- [x] Remover URLs de categoria com query string.
- [x] Usar `lastmod` baseado em dados reais.

---

## Fase SEO 24 — Robots e páginas noindex

- [x] Revisar `public/robots.txt`.
- [x] Preservar proteção de `/admin/`.
- [x] Preservar proteção de áreas privadas.
- [x] Garantir sitemap absoluto.
- [x] Aplicar `noindex` ao carrinho.
- [x] Aplicar `noindex` aos favoritos.
- [x] Aplicar `noindex` ao checkout.
- [x] Aplicar `noindex` ao login.
- [x] Aplicar `noindex` ao register.
- [x] Aplicar `noindex` ao dashboard/área privada.
- [x] Não bloquear CSS.
- [x] Não bloquear JavaScript.
- [x] Não bloquear imagens públicas necessárias.

---

## Fase SEO 25 — Links internos

- [x] Adicionar links da Home para categorias principais.
- [x] Adicionar links das categorias para produtos.
- [x] Adicionar links dos produtos para sua categoria.
- [x] Adicionar produtos relacionados semanticamente.
- [x] Relacionar artigos e produtos quando houver conexão real.
- [x] Garantir links para o blog.
- [x] Evitar links artificiais criados somente para SEO.
- [x] Evitar páginas órfãs.

---

## Fase SEO 26 — Headings

- [x] Auditar H1/H2/H3 da Home.
- [x] Auditar H1/H2/H3 do catálogo.
- [x] Auditar H1/H2/H3 das categorias.
- [x] Auditar H1/H2/H3 de produto.
- [x] Auditar H1/H2/H3 do blog.
- [x] Auditar H1/H2/H3 dos artigos.
- [x] Manter apenas um H1 principal por página.
- [x] Não utilizar headings somente por aparência visual.

---

## Fase SEO 27 — Imagens

- [x] Auditar ALT das imagens.
- [x] Usar ALT descritivo e natural.
- [x] Não fazer keyword stuffing em ALT.
- [x] Preservar WebP quando existente.
- [x] Garantir `width`/`height` quando possível.
- [x] Usar lazy loading fora do conteúdo prioritário.
- [x] Não usar lazy loading na imagem principal/LCP quando prejudicial.
- [x] Evitar CLS causado por imagens.

---

## Fase SEO 28 — Performance e Core Web Vitals

- [x] Avaliar tamanho do HTML da Home.
- [x] Avaliar tamanho do HTML das páginas de produto.
- [x] Avaliar CSS inline via `Vite::content()`.
- [x] Avaliar impacto das fontes.
- [x] Avaliar imagens.
- [x] Avaliar JavaScript.
- [x] Avaliar preload.
- [x] Evitar mudanças especulativas sem benefício.
- [x] Priorizar LCP.
- [x] Priorizar CLS.
- [x] Priorizar INP.
- [x] Evitar regressão visual.

---

## Fase SEO 29 — Conteúdo duplicado

- [x] Remover descrição completa dos cards da Home.
- [x] Remover descrição completa dos cards do catálogo.
- [x] Remover descrição completa dos produtos relacionados.
- [x] Usar `short_description`.
- [x] Criar fallback curto.
- [x] Manter conteúdo completo principalmente na página individual.
- [x] Não copiar automaticamente conteúdo de fornecedores.
- [x] Não gerar automaticamente textos SEO no banco.

---

## Fase SEO 30 — Produtos de terceiros e autoria

- [x] Separar conceito de vendedor e desenvolvedor.
- [x] KL Tecnologia deve continuar como `seller` quando apropriado.
- [x] Não usar KL automaticamente como `brand`.
- [x] Não usar KL automaticamente como desenvolvedora.
- [x] Não afirmar autoria de produtos de terceiros.
- [x] Permitir cadastro da marca/desenvolvedor verdadeiro.
- [x] Omitir marca quando desconhecida.

---

## Fase SEO 31 — Páginas de categoria com conteúdo editorial

- [x] Exibir H1 da categoria.
- [x] Exibir descrição introdutória.
- [x] Exibir grade de produtos.
- [x] Preparar estrutura para conteúdo adicional futuro.
- [x] Não gerar textos longos automaticamente.
- [x] Utilizar conteúdo informado pelo administrador.

---

## Fase SEO 32 — Status HTTP e erros

- [x] Produto inexistente deve retornar 404.
- [x] Produto indisponível deve seguir regra consistente do projeto.
- [x] Post inexistente deve retornar 404.
- [x] URL antiga conhecida deve retornar 301.
- [x] URL realmente inexistente deve retornar 404.
- [x] Não redirecionar 404 globalmente para Home.

---

## Fase SEO 33 — Testes de produto

- [x] Testar title.
- [x] Testar meta description.
- [x] Testar canonical.
- [x] Testar robots.
- [x] Testar Open Graph.
- [x] Testar Product JSON-LD.
- [x] Testar Offer.
- [x] Testar `BRL`.
- [x] Testar seller.
- [x] Testar brand quando existir.
- [x] Testar ausência de brand quando não existir.
- [x] Testar ausência de `priceValidUntil` inventado.
- [x] Testar ausência de reviews fictícias.
- [x] Testar breadcrumb com categoria real.

---

## Fase SEO 34 — Testes de categoria

- [x] Testar URL limpa.
- [x] Testar status HTTP 200.
- [x] Testar H1.
- [x] Testar title.
- [x] Testar meta description.
- [x] Testar canonical.
- [x] Testar produtos somente da categoria.
- [x] Testar `index,follow`.

---

## Fase SEO 35 — Testes de filtros

- [x] Testar `?q=` com `noindex,follow`.
- [x] Testar `?sort=` com `noindex,follow`.
- [x] Testar `?min_price=` com `noindex,follow`.
- [x] Testar `?max_price=` com `noindex,follow`.
- [x] Testar combinação de filtros.
- [x] Testar canonical resultante.

---

## Fase SEO 36 — Testes do blog

- [x] Testar Article schema.
- [x] Testar canonical.
- [x] Testar SEO title personalizado.
- [x] Testar meta description personalizada.
- [x] Testar fallback do SEO title.
- [x] Testar fallback da meta description.
- [x] Testar categoria do blog.
- [x] Testar breadcrumbs.

---

## Fase SEO 37 — Testes do sitemap

- [x] Testar produto ativo presente.
- [x] Testar produto inativo ausente.
- [x] Testar produto indisponível ausente.
- [x] Testar categoria válida presente.
- [x] Testar categoria vazia ausente.
- [x] Testar post publicado presente.
- [x] Testar draft ausente.
- [x] Testar categoria do blog válida.
- [x] Testar ausência de URLs de busca.
- [x] Testar ausência de filtros.
- [x] Testar URLs limpas.

---

## Fase SEO 38 — Testes de redirects

- [x] Testar slug antigo de produto.
- [x] Confirmar HTTP 301.
- [x] Confirmar destino canônico.
- [x] Testar slug antigo de artigo.
- [x] Testar slug antigo de categoria.
- [x] Testar slug antigo de categoria de blog.
- [x] Testar ausência de redirect chain.

---

## Fase SEO 39 — Testes de avaliações

- [x] Confirmar remoção de `48 avaliações de clientes verificados`.
- [x] Confirmar remoção de `100% dos compradores avaliaram como excelente`.
- [x] Confirmar ausência de depoimentos fictícios.
- [x] Confirmar ausência de Review schema sem dados reais.

---

## Fase SEO 40 — Validação final

- [x] Executar `php artisan test`.
- [x] Corrigir todos os testes quebrados.
- [x] Executar `npm run build`.
- [x] Corrigir erros de build.
- [x] Executar `./vendor/bin/pint --test`.
- [x] Corrigir estilo quando necessário.
- [x] Executar testes novamente após Pint.
- [x] Executar `php artisan route:list`.
- [x] Conferir rotas novas.
- [x] Conferir que checkout continua funcionando.
- [x] Conferir autenticação.
- [x] Conferir produtos.
- [x] Conferir downloads.
- [x] Conferir painel administrativo.
- [x] Conferir blog.
- [x] Conferir newsletter.
- [x] Conferir webhooks.
- [x] Conferir integração Mercado Pago.

---

## Fase SEO 41 — Documentação e encerramento

- [x] Atualizar este `TASKS.md` marcando todas as tarefas efetivamente concluídas.
- [x] Não marcar tarefas pendentes como concluídas.
- [x] Registrar arquivos criados.
- [x] Registrar arquivos alterados.
- [x] Registrar migrations.
- [x] Registrar novas rotas.
- [x] Registrar campos novos do admin.
- [x] Registrar estratégia de canonical.
- [x] Registrar estratégia de noindex.
- [x] Registrar mudanças de Product JSON-LD.
- [x] Registrar mudanças do sitemap.
- [x] Registrar redirects implementados.
- [x] Registrar resultado de `php artisan test`.
- [x] Registrar resultado de `npm run build`.
- [x] Registrar resultado do Pint.
- [x] Listar qualquer pendência real.
- [x] Não otimizar individualmente os produtos nesta fase.

---

# Definição de concluído

Este bloco de SEO somente poderá ser considerado concluído quando:

- [x] A infraestrutura permitir SEO individual por produto.
- [x] Produtos diferentes puderem ter requisitos diferentes.
- [x] Produtos diferentes puderem ter licenças diferentes.
- [x] Produtos sem código-fonte não forem apresentados como código-fonte.
- [x] Avaliações fictícias tiverem sido removidas.
- [x] Categorias tiverem URLs próprias e limpas.
- [x] Filtros não criarem páginas indexáveis desnecessárias.
- [x] Canonicals estiverem coerentes.
- [x] Sitemap estiver usando somente URLs canônicas.
- [x] Product JSON-LD contiver somente dados verdadeiros.
- [x] Blog estiver preparado para SEO editorial.
- [x] Slugs antigos puderem ser redirecionados com HTTP 301.
- [x] Todos os testes passarem.
- [x] Build de produção passar.
- [x] O cadastro individual de produtos puder começar sem necessidade de nova alteração estrutural grande.

---

# Revisão pós-implementação SEO

- [x] 1. Corrigir campos inexistentes da página de produto: referências a `$product->license_info` e `$product->brand_name` substituídas por `$product->license` e `$product->brand`; campos não preenchidos não geram textos fictícios; testes adicionados.
- [x] 2. Remover preços fictícios: removido `$product->regular_price`, cálculo artificial `* 1.35`, fallback `47.00` e tag `<del>De: R$ ...</del>`; preço exibido reflete exclusivamente o valor cadastrado; produtos gratuitos só exibem "acesso vitalício" quando `lifetime_access === true`.
- [x] 3. Corrigir SEO dos posts: persistência de `seo_title` e `meta_description` implementada em `Admin\PostController::store()` e `update()`; suporte a limpeza dos campos; regras de validação padronizadas em `StorePostRequest` e `UpdatePostRequest` (`max:70` para título, `max:160` para descrição); testes adicionados.
- [x] 4. Corrigir duplicação do nome KL Tecnologia nos titles: layout `layouts/storefront.blade.php` recebe o título SEO final diretamente sem duplicar marca; fallbacks geram títulos com a marca quando o campo SEO estiver vazio; nunca gera "KL Tecnologia | KL Tecnologia"; testes verificam tags `<title>` exatas para produto, categoria e post (com e sem `seo_title`).
- [x] 5. Corrigir canonical da paginação: páginas indexáveis paginadas agora geram canonical com `?page=N` (`/catalogo?page=2`, `/catalogo/{slug}?page=2`, `/blog?page=2`, `/blog/categoria/{slug}?page=2`); buscas e filtros mantêm `noindex, follow` e canonicalizam para a landing page base; testes específicos adicionados.
- [x] 6. Corrigir redirects legados do catálogo: suporte adicionado tanto para `?category=slug` quanto para `?categoria=slug`, redirecionando com HTTP 301 para a rota limpa `/catalogo/{slug}` quando não houver filtros adicionais; links internos mantêm URLs limpas; testes adicionados para ambas as variantes.
- [x] 7. Corrigir estratégia robots/noindex: removido do `public/robots.txt` o bloqueio a páginas públicas transacionais (`/checkout`, `/carrinho`, `/favoritos`, `/login`, `/register`, `/password/`), permitindo que os robôs acessem e leiam a tag `<meta name="robots" content="noindex, follow">`; mantido bloqueio apenas para áreas restritas (`/admin/` e `/customer/`); testes atualizados.
- [x] 8. Limpar Product JSON-LD: propriedades nulas (`image`, `brand`, `category`) omitidas completamente via `array_filter` no JSON-LD do produto; preservadas propriedades essenciais (`Product`, `Offer`, `seller`, `price`, `priceCurrency`, `availability`, `sku`); nenhuma avaliação ou `aggregateRating` fictício gerado; testes adicionados.
- [x] 9. Article JSON-LD: omitida propriedade `image` do artigo quando não houver capa (`cover_path` nulo), sem usar o logo institucional como imagem da matéria; logo mantido em `publisher.logo`; testes adicionados.
- [x] 10. Corrigir lastmod artificial: removido `now()->startOfMonth()` de Política de Privacidade e Termos de Uso no `SitemapController`; utilizado timestamp real do arquivo (`filemtime`) com tag `<lastmod>` opcional no template XML; testes adicionados.
- [x] 11. Atualizar DATABASE-SCHEMA.md: documentados todos os novos campos de `products`, `categories`, `blog_categories`, `posts` e a tabela `slug_redirects`.
- [x] 12. Corrigir/limitar o scraper PLW: preservação integral do conteúdo editorial e SEO curado manualmente (`description`, `short_description`, `seo_title`, `meta_description`, `features`, `requirements`, `license`, `brand`, `product_type`, `support_info`, `price`); removido fallback genérico de código-fonte; extração de preço aprimorada para priorizar preço de venda e `<ins>`, nunca assumindo `<del>` como preço de venda; testes adicionados.
- [x] 13. Testes e validações de qualidade: 260 testes passando (`php artisan test`), build Vite concluído com sucesso (`npm run build`), código formatado e validado pelo Laravel Pint (`php vendor/bin/pint --test`), e todas as 100 rotas íntegras (`php artisan route:list`).

---

## Hardening final pré-SEO editorial

- [x] Corrigir advisory de segurança do league/commonmark (atualizado para 2.10.3 via composer, composer audit com 0 vulnerabilidades).
- [x] Restaurar CI verde no GitHub Actions (composer audit, npm audit, migrations, pint, testes).
- [x] Permitir controle manual de slug no admin (Produto, Categoria, Post e Categoria do Blog) com helper explicativo sobre HTTP 301.
- [x] Garantir integridade de slugs (normalização via Str::slug, unicidade com SoftDeletes, precedência do registro ativo sobre redirects históricos e achatamento de cadeias A -> C).
- [x] Validar redirects de slug nas quatro entidades com testes automatizados dedicados (`SlugLifecycleAndRedirectTest`).
- [x] Remover alegações comerciais não sustentadas de produtos ("testados", "verificados", "arquivos verificados e prontos", "projetos testados e livres de vírus").
- [x] Corrigir rótulo "Código Fonte Aberto" para factual "Código Fonte Incluso" quando `includes_source_code = true`.
- [x] Remover versão 1.0 automática e ocultar o campo versão no storefront quando `null`.
- [x] Corrigir rótulo de `updated_at` na ficha do produto para "Anúncio atualizado".
- [x] Corrigir comunicação institucional de autoria (remover "pioneira", "scripts autorais", "centenas de sistemas", "sistemas prontos e validados", e alinhar "Suporte Dedicado" para "Atendimento ao Cliente").
- [x] Remover fallback de artigos não relacionados na página de produto (ocultando a seção quando não houver posts pertinentes).
- [x] Criar testes de cobertura para todas as alterações (`StorefrontHardeningFactualContentTest` e `SlugLifecycleAndRedirectTest`).

## Correção de JSON-LD no Blade

- [x] Gerar a chave de contexto por concatenação (`'@'.'context'`) no layout global e nas páginas de produto e post, evitando sua interpretação como diretiva Blade.
- [x] Adicionar `JsonLdTest` para capturar todos os scripts JSON-LD de Home, Produto e Post, decodificar cada bloco e verificar `JSON_ERROR_NONE`, o contexto `https://schema.org` e a ausência de PHP no JSON.
- [x] Reproduzir a regressão antes da correção e validar a suíte completa após a alteração: 283 testes passando (1327 assertions), Pint e build Vite aprovados.
- [x] Registrar a correção como versão patch `2.1.1` em `config/changelog.php` e `VERSOES.md`.

## Correção do botão de produtos — v2.1.2

- [x] Corrigir a serialização do estado inicial do formulário com `@js`, evitando erros JavaScript que ocultavam o texto do botão.
- [x] Usar `btn-primary` com rótulos `Salvar alterações` e `Criar produto`, foco visível, contraste adequado e `Cancelar` ao lado, preservando a lógica de submit.
- [x] Adicionar testes de renderização nas telas de criação e edição.
- [x] Verificar no navegador em desktop (1440px) e mobile (375px), incluindo texto, dimensões, foco, estado de envio e recuperação de erro de validação.
- [x] Registrar a versão patch `2.1.2` no changelog e em `VERSOES.md`.
- [x] Validar a suíte completa (285 testes, 1337 assertions), build Vite, Laravel Pint, `git diff --check` e auditoria visual estática sem apontamentos.
