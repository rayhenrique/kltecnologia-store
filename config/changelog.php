<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Versão Atual da Aplicação
    |--------------------------------------------------------------------------
    |
    | Define a versão corrente da plataforma KL Tecnologia Store.
    | Toda vez que uma nova release entrar em produção, atualize este valor.
    |
    */

    'current_version' => env('APP_VERSION', '2.1.1'),

    /*
    |--------------------------------------------------------------------------
    | Histórico Completo de Releases e Changelog
    |--------------------------------------------------------------------------
    |
    | Cada release contém os metadados da versão e o público-alvo:
    | - audience: 'admin' (apenas administradores), 'customer' (apenas clientes) ou 'all' (todos).
    | - changes: lista de alterações com type (feature, improvement, fix, security),
    |   title, description e audience opcional.
    |
    */

    'releases' => [
        '2.1.1' => [
            'version' => '2.1.1',
            'date' => '2026-10-03',
            'title' => 'Correção dos Dados Estruturados JSON-LD',
            'audience' => 'admin',
            'summary' => 'Correção da chave de contexto nos dados estruturados da Home e das páginas de produto e artigo.',
            'changes' => [
                [
                    'type' => 'fix',
                    'title' => 'Contexto JSON-LD Preservado',
                    'description' => 'Os schemas da Home, dos produtos e dos artigos preservam o contexto https://schema.org durante a compilação Blade, com testes para validar todos os blocos JSON-LD dessas páginas.',
                    'audience' => 'admin',
                ],
            ],
        ],

        '2.1.0' => [
            'version' => '2.1.0',
            'date' => '2026-09-16',
            'title' => 'Módulo de Gestão de Clientes, Central de Versões & Otimização de Fila',
            'audience' => 'all',
            'summary' => 'Nova central administrativa para acompanhamento de clientes, automações avançadas de fila da newsletter e central de versões com notificações aos usuários.',
            'changes' => [
                [
                    'type' => 'feature',
                    'title' => 'Gestão Completa de Clientes no Painel Admin',
                    'description' => 'Métricas em tempo real de clientes (LTV, ticket médio, histórico de compras), filtros por perfil/compradores e botão direto de atendimento via WhatsApp.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Central de Notificações de Versões & Novidades',
                    'description' => 'Avisos automáticos de novas funcionalidades e melhorias no primeiro acesso após cada atualização, com segmentação inteligente entre Administradores e Clientes.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'CRUD e Histórico Detalhado de Newsletter',
                    'description' => 'Acompanhamento individual de leads inscritos, histórico de notificações recebidas, toggle ativo/inativo e exportação de relatórios em CSV com UTF-8 BOM.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Escalonamento de Fila com Limite Diário (100/dia)',
                    'description' => 'Processamento contínuo de newsletters com cota de segurança diária para blindagem de reputação do domínio e adiamento automático para o dia seguinte.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Painel de Meus Pedidos & Downloads Aprimorado',
                    'description' => 'Visualização mais rápida dos seus links de download e status de compensação de pagamentos na área do cliente.',
                    'audience' => 'customer',
                ],
            ],
        ],

        '2.0.0' => [
            'version' => '2.0.0',
            'date' => '2026-09-14',
            'title' => 'E-Commerce 2.0: Carrinho, Favoritos & Checkout Integrado',
            'audience' => 'all',
            'summary' => 'Reformulação completa do ecossistema de compras, permitindo cadastro instantâneo no checkout, múltiplos itens no carrinho e lista de favoritos.',
            'changes' => [
                [
                    'type' => 'feature',
                    'title' => 'Carrinho de Compras e Checkout Integrado',
                    'description' => 'Adicione múltiplos sistemas ou scripts ao carrinho e finalize seu pedido com cadastro automático e cálculo dinâmico de cupons.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Sistema de Favoritos Reativo',
                    'description' => 'Salve produtos em sua lista de desejos para comparar recursos ou adquirir posteriormente com sincronização instantânea via Alpine.js.',
                    'audience' => 'customer',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Downloads Gratuitos Imediatos (Lead Magnets)',
                    'description' => 'Acesso imediato a scripts e conteúdos 100% gratuitos com liberação automática sem redirecionar ao gateway de pagamento.',
                    'audience' => 'customer',
                ],
                [
                    'type' => 'security',
                    'title' => 'Conformidade LGPD & Gestão de Cookies',
                    'description' => 'Banner de privacidade interativo com controle granular de preferências de navegação (Essenciais, Analíticos e Marketing).',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Analytics Nativo de Tráfego & Visitas',
                    'description' => 'Painel com métricas de conversão em tempo real, visitantes únicos, proporção mobile vs desktop e ranking dos produtos mais populares sem impactar a latência.',
                    'audience' => 'admin',
                ],
            ],
        ],

        '1.5.0' => [
            'version' => '1.5.0',
            'date' => '2026-09-13',
            'title' => 'Módulo de Cupons de Desconto, Newsletter & Otimização WebP',
            'audience' => 'all',
            'summary' => 'Novos mecanismos promocionais no checkout, captação de leads da newsletter e conversão automática de imagens para formato WebP.',
            'changes' => [
                [
                    'type' => 'feature',
                    'title' => 'Módulo Completo de Cupons de Desconto',
                    'description' => 'Criação de cupons percentuais e fixos no painel admin com agendamento de vigência, limite de utilizações e regras por produto específico.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Captação e Exportação de Newsletter',
                    'description' => 'Formulário reativo com envio assíncrono na vitrine e exportação de leads em streaming CSV para Excel/Google Sheets.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Otimização de Imagens em WebP',
                    'description' => 'Conversão e redimensionamento automático de imagens de capas para WebP, acelerando o carregamento da vitrine.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Produtos em Destaque na Home',
                    'description' => 'Opção para marcar produtos em destaque na página inicial com ordenação cronológica automática do catálogo.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Sitemap Dinâmico XML & Schema.org JSON-LD',
                    'description' => 'Geração automática de sitemap.xml com dados estruturados para Google e redes sociais.',
                    'audience' => 'all',
                ],
            ],
        ],

        '1.3.0' => [
            'version' => '1.3.0',
            'date' => '2026-09-13',
            'title' => 'Redesign do Painel Admin, Categorias & Mobile First',
            'audience' => 'all',
            'summary' => 'Aprimoramentos de usabilidade no painel administrativo, menu lateral retrátil, categorias de produtos e artigos, e busca global Spotlight.',
            'changes' => [
                [
                    'type' => 'feature',
                    'title' => 'Módulo de Categorias de Produtos e Blog',
                    'description' => 'Gestão de categorias com slug amigável, ícones e contadores dinâmicos de produtos e artigos vinculados.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Sidebar Retrátil no Painel Admin',
                    'description' => 'Menu lateral com alternância suave entre modo expandido e modo recolhido (somente ícones) com persistência no navegador.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Busca Global Spotlight (Ctrl+K / Cmd+K)',
                    'description' => 'Modal de busca instantânea com atalhos de teclado e tags de buscas em alta.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Redesign da Página de Perfil (/profile)',
                    'description' => 'Hero banner moderno escuro, gestão de CPF para emissão de pedidos e alteração segura de senha.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Identidade Visual & Logotipo Oficial',
                    'description' => 'Integração do logotipo oficial da KL Tecnologia, favicon e tags de aplicativo em todos os layouts.',
                    'audience' => 'all',
                ],
            ],
        ],

        '1.1.0' => [
            'version' => '1.1.0',
            'date' => '2026-09-12',
            'title' => 'Redesign da Página do Produto, Scraper & E-mails Transacionais',
            'audience' => 'all',
            'summary' => 'Nova interface rica de apresentação de produtos, ferramentas de importação em lote e notificações automatizadas por e-mail.',
            'changes' => [
                [
                    'type' => 'feature',
                    'title' => 'Redesign da Página de Detalhes do Produto',
                    'description' => 'Abas interativas com descrição rica, requisitos técnicos, avaliações verificadas, sticky purchase bar e badges de garantia.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Scraper de Catálogo e Blog PLW Design',
                    'description' => 'Comandos Artisan automatizados para importação estruturada de produtos, artigos e download de capas em alta resolução.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'E-mails Transacionais com Layout SaaS',
                    'description' => 'Disparo resiliente de mensagens de Boas-Vindas, Pedido Pendente, Compra Aprovada e Notificação de Venda ao Administrador.',
                    'audience' => 'all',
                ],
            ],
        ],

        '1.0.0' => [
            'version' => '1.0.0',
            'date' => '2026-09-12',
            'title' => 'Lançamento Oficial da Plataforma KL Tecnologia Store',
            'audience' => 'all',
            'summary' => 'Entrada em produção da loja oficial com catálogo de produtos digitais, blog técnico, integração Mercado Pago e entrega via links assinados.',
            'changes' => [
                [
                    'type' => 'feature',
                    'title' => 'Vitrine de Produtos Digitais & Blog Integrado',
                    'description' => 'Apresentação de sistemas SaaS, artigos técnicos e especificações detalhadas dos códigos-fonte para desenvolvedores e empresas.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'security',
                    'title' => 'Armazenamento Privado & Links Assinados',
                    'description' => 'Arquivos binários dos produtos protegidos em disco privado inacessível publicamente, com download temporário seguro após confirmação de pagamento.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Integração Mercado Pago com Webhooks Idempotentes',
                    'description' => 'Aprovação em tempo real de pagamentos via PIX e Cartão de Crédito com conciliação automática de status.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Painel Administrativo Base',
                    'description' => 'Gestão de produtos, controle de pedidos e permissões isoladas por papéis de acesso (Admin e Cliente).',
                    'audience' => 'admin',
                ],
            ],
        ],
    ],
];
