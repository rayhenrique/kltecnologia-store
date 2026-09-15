# Histórico de Versões e Atualizações (Changelog) - KL Tecnologia

Este documento registra todas as versões, novos recursos, melhorias de arquitetura, correções e atualizações de segurança implementadas na plataforma **KL Tecnologia Store** desde o lançamento inicial em produção até a versão atual.

Seguimos a convenção de [Semantic Versioning](https://semver.org/lang/pt-BR/) (`MAJOR.MINOR.PATCH`).

---

## 🏷️ Sumário Rápido de Versões
- [v2.1.0 (2026-09-16) - Módulo de Gestão de Clientes & Otimização de Fila](#v210---2026-09-16)
- [v2.0.0 (2026-09-14) - E-Commerce 2.0: Carrinho, Favoritos & Checkout Integrado](#v200---2026-09-14)
- [v1.5.0 (2026-09-13) - Módulo de Newsletter, Cupons & Analytics Nativo](#v150---2026-09-13)
- [v1.3.0 (2026-09-13) - Redesign do Painel Admin, Categorias & Mobile First](#v130---2026-09-13)
- [v1.1.0 (2026-09-12) - Redesign da Vitrine, Scraper & E-mails Transacionais](#v110---2026-09-12)
- [v1.0.0 (2026-09-12) - Lançamento Oficial da Plataforma](#v100---2026-09-12)

---

## [v2.1.0] - 2026-09-16
*Fase 38 a 40 do Roadmap de Desenvolvimento*

### ✨ Novas Funcionalidades
- **Módulo Completo de Gestão de Clientes (`/admin/customers`):**
  - Painel administrativo com 4 cards de KPIs em tempo real (Total de Clientes, Compradores Ativos, Novos no Mês e LTV Total).
  - Listagem com busca global por nome, e-mail, CPF e telefone, além de filtros por perfil e compradores.
  - Tela de perfil detalhado (`show.blade.php`) com histórico completo de compras, métricas individuais (LTV, ticket médio), dados de conformidade LGPD e botão de contato direto via WhatsApp.
  - CRUD completo com bloqueio de autoexclusão de administradores e proteção de integridade contra deleção de clientes com compras pagas.
- **Módulo Administrativo Completo de Newsletter (`/admin/newsletter`):**
  - Gestão de leads cadastrados com criação manual, edição de status e toggle instantâneo ativo/inativo.
  - Tela detalhada do lead (`show.blade.php`) exibindo metadados técnicos (IP, User-Agent), vínculo com conta de cliente, URL assinada de opt-out e histórico de disparos recebidos.
- **Sistema Nativo de Notificação de Novidades (Changelog):**
  - Componente modal interativo pós-login com detecção automática de versões não visualizadas.
  - Segmentação inteligente de comunicados por público-alvo (`admin`, `customer`, `all`).
  - Badge interativo na sidebar do painel administrativo com atalho rápido para consulta do histórico.

### ⚡ Melhorias & Performance
- **Escalonamento da Fila de Newsletter com Limite Diário (100/dia):**
  - Implementação de cota diária de segurança para proteção de reputação e entregabilidade de SMTP.
  - Adiamento inteligente de jobs (`$this->release()`) para as 00:05 do dia subsequente quando a cota atinge o teto.
- **Suíte de Testes Automatizados:**
  - Adição de 20 novos testes de integração e feature, elevando a suíte geral da aplicação para mais de 235 testes 100% aprovados.

---

## [v2.0.0] - 2026-09-14
*Fase 21 a 37 do Roadmap de Desenvolvimento*

### ✨ Novas Funcionalidades
- **Fluxo de Compra e Checkout com Cadastro Integrado:**
  - Página dedicada de checkout (`/checkout`) com suporte a compra direta ou itens do carrinho.
  - Criação automática e transparente de conta de acesso para visitantes com autenticação pós-compra.
  - Suporte a produtos 100% gratuitos (Lead Magnets) com liberação imediata sem acionamento do Mercado Pago.
- **Página de Carrinho de Compras (`/carrinho`):**
  - Carrinho responsivo com suporte reativo (`localStorage` + Alpine.js), cálculo em tempo real de subtotais e cupons.
  - Notificações toast ao adicionar produtos pelo catálogo ou vitrine.
- **Página de Favoritos (`/favoritos`):**
  - Gestão reativa de lista de desejos integrada em todas as páginas da loja (cards, navbar e menu mobile).
- **Módulo Nativo de Métricas de Tráfego & Analytics:**
  - Rastreamento seguro com hash anônimo (LGPD) e sem impacto na latência (`dispatchAfterResponse`).
  - Painel com comparação diária de visitas, taxa de conversão em tempo real, proporção mobile vs desktop e ranking dos produtos mais populares.

### 🛡️ Segurança & Privacidade
- **Central de Privacidade & Consentimento de Cookies (LGPD - Lei 13.709/2018):**
  - Banner flutuante com escolha granular (Essenciais, Preferências, Analíticos e Marketing).
  - Páginas públicas dedicadas para Política de Privacidade (`/politica-de-privacidade`) e Termos de Uso (`/termos-de-uso`).
  - Exigência de aceite ativo de termos e política no checkout.
- **Hardening de Produção:**
  - Bloqueio rigoroso de checkout e catálogo para produtos sem arquivo digital vinculado.
  - Sanitização profunda de HTML com HTMLPurifier em artigos e descrições.
  - Remoção total de credenciais e senhas embutidas em código ou sessão.

---

## [v1.5.0] - 2026-09-13
*Fase 26 a 30 do Roadmap de Desenvolvimento*

### ✨ Novas Funcionalidades
- **Módulo Administrativo de Cupons de Desconto (`/admin/coupons`):**
  - Criação de cupons percentuais ou fixos, escopo geral ou por produto, agendamento de vigência e teto de utilizações.
  - Validação assíncrona em tempo real (`POST /cupons/validar`) no checkout e carrinho.
- **Produtos em Destaque na Home:**
  - Coluna `is_featured` para priorização manual de sistemas e ordenação cronológica automática do catálogo.
- **Captação e Exportação de Newsletter:**
  - Formulário interativo na vitrine com envio assíncrono via fetch e exportação de leads em streaming CSV com codificação UTF-8 com BOM para Excel.

### ⚡ Otimização & SEO
- **Otimização de Imagens para WebP:**
  - Conversão automática de capas em uploads para `.webp` com redimensionamento proporcional.
- **Infraestrutura Técnica de SEO & Dados Estruturados:**
  - Sitemap dinâmico em XML (`/sitemap.xml`) e diretivas otimizadas no `robots.txt`.
  - JSON-LD Schema.org para `Organization`, `WebSite`, `Product` e `Article`.

---

## [v1.3.0] - 2026-09-13
*Fase 11 a 20 do Roadmap de Desenvolvimento*

### ✨ Novas Funcionalidades
- **Módulo de Categorias de Produtos e Blog no Painel Admin:**
  - Gestão de categorias com slug único e contagem dinâmica de itens vinculados.
- **Sidebar Retrátil no Painel Admin:**
  - Suporte a modo recolhido (somente ícones) e expandido com persistência em `localStorage`.
- **TopBar Interativa & Modal de Busca Global (Spotlight):**
  - Atalhos de teclado (`Ctrl+K` / `Cmd+K` / `ESC`) para localização instantânea de produtos e artigos.
- **Identidade Visual Oficial:**
  - Integração do logotipo oficial da KL Tecnologia, favicon e badges institucionais.
- **Redesign da Página de Perfil (`/profile`):**
  - Banner moderno Dark SaaS, gestão de dados fiscais (CPF/WhatsApp) e alteração segura de senha.

---

## [v1.1.0] - 2026-09-12
*Fase 8 a 10 e 29 do Roadmap de Desenvolvimento*

### ✨ Novas Funcionalidades
- **Redesign Completo da Página de Detalhes do Produto:**
  - Layout de 2 colunas com abas interativas (Descrição, Requisitos, Depoimentos), sidebar de compra sticky e badges de confiança.
- **Web Scraper Automatizado para Catálogo e Blog:**
  - Comandos Artisan `app:scrape-plw` e `app:scrape-plw-blog` para migração e atualização de catálogo e postagens com download local de capas.
- **E-mails Transacionais com Layout SaaS Dark/Light:**
  - Notificações de Boas-Vindas (`WelcomeCustomerMail`), Pedido Pendente (`OrderPendingMail`), Compra Confirmada (`OrderPaidMail`) e Alerta ao Administrador (`AdminNewOrderMail`).

---

## [v1.0.0] - 2026-09-12
*Fase 1 a 7 do Roadmap de Desenvolvimento*

### 🚀 Lançamento Inicial
- Estrutura base Laravel 13.x com PHP 8.3 e MySQL.
- Autenticação com Laravel Breeze e controle de acesso baseado em papéis (`UserRole::Admin` e `UserRole::Customer`).
- CRUD inicial de Produtos e Pedidos no painel administrativo.
- Armazenamento privado de arquivos digitais em `storage/app/digital_products` e liberação via links assinados temporários.
- Integração com gateway Mercado Pago (Checkout Pro e PIX) com tratamento assíncrono via Webhooks idempotentes.
