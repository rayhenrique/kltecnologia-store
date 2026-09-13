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
  - [x] Criar `ProcessCheckoutRequest` com validações robustas de conta, produtos ativos e cupons de desconto (`VIP10`, `KL2026`).
  - [x] Aprimorar `CheckoutService::process` para cadastrar visitante, efetuar login automático com evento `Registered`, instanciar pedidos e gerar preferência no Mercado Pago.
  - [x] Expandir `MercadoPagoService` e `WebhookService` para suportar pedidos individuais e múltiplos com conciliação idempotente de pagamentos.
  - [x] Atualizar botão "Comprar" na página do produto (`storefront.show`) e na barra fixa mobile para direcionar diretamente ao checkout sem barreiras de login prévio.
  - [x] Atualizar botão de finalização no carrinho de compras (`cart.index`) para link unificado de checkout.
  - [x] Reformular página "Meus Downloads & Pedidos" (`customer.downloads`) para exibir status em tempo real de pedidos pagos (com link assinado) e pedidos pendentes (com aviso de confirmação).
  - [x] Expandir suíte de testes Feature (`CheckoutTest` e `CartTest`) alcançando 100% de aprovação (114 testes, 452 asserções) e conformidade estrita no Pint.

- [x] **Fase 22: Produtos Gratuitos (Lead Magnet) & Liberação Direta sem Mercado Pago**
  - [x] Ajustar validação de criação e edição de produtos no painel Admin (`StoreProductRequest` e `UpdateProductRequest`) permitindo preço zero (`min:0`).
  - [x] Ajustar validação do formulário de checkout (`ProcessCheckoutRequest`) com detecção de pedido gratuito (`isFreeOrder()`), tornando CPF e telefone opcionais e preservando cadastro simples de leads (Nome, E-mail, Senha).
  - [x] Atualizar `CheckoutService::start` e `CheckoutService::process` para pedidos com valor zero (`$totalAmount <= 0`) ou cupons de 100% (`FREE100` / `GRATIS100`):
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



