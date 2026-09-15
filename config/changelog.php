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

    'current_version' => env('APP_VERSION', '2.1.0'),

    /*
    |--------------------------------------------------------------------------
    | Histórico de Releases e Changelog
    |--------------------------------------------------------------------------
    |
    | Cada release contém os metadados da versão e o público-alvo:
    | - audience: 'admin' (apenas administradores), 'customer' (apenas clientes) ou 'all' (todos).
    | - changes: lista de alterações com type (feature, improvement, fix, security),
    |   title, description e audience opcional.
    |
    */

    'releases' => [
        '2.1.0' => [
            'version' => '2.1.0',
            'date' => '2026-09-16',
            'title' => 'Módulo de Gestão de Clientes & Otimização de Fila',
            'audience' => 'all',
            'summary' => 'Nova central administrativa para acompanhamento de clientes, automações avançadas de fila da newsletter e melhorias de usabilidade na loja.',
            'changes' => [
                [
                    'type' => 'feature',
                    'title' => 'Gestão Completa de Clientes no Painel Admin',
                    'description' => 'Métricas em tempo real de clientes (LTV, ticket médio, histórico de compras), filtros por status e botão direto de atendimento via WhatsApp.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'CRUD e Histórico Detalhado de Newsletter',
                    'description' => 'Acompanhamento individual de leads inscritos, histórico de notificações de produtos recebidas e exportação de relatórios em CSV.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Escalonamento Inteligente de Fila com Limite Diário',
                    'description' => 'Processamento automático com limite de segurança de 100 envios diários, protegendo a reputação e entregabilidade do domínio.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Central de Notificações de Novidades',
                    'description' => 'Avisos automáticos de novas funcionalidades e melhorias no seu primeiro acesso após cada atualização do sistema.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Painel de Meus Pedidos & Downloads Aprimorado',
                    'description' => 'Visualização mais rápida dos seus links de download e status de compensação de pagamentos.',
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
                    'description' => 'Salve produtos em sua lista de desejos para comparar recursos ou adquirir posteriormente com sincronização instantânea.',
                    'audience' => 'customer',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Downloads Gratuitos Imediatos (Lead Magnets)',
                    'description' => 'Acesso imediato a scripts e conteúdos 100% gratuitos sem passar por gateway de pagamento.',
                    'audience' => 'customer',
                ],
                [
                    'type' => 'security',
                    'title' => 'Conformidade LGPD & Gestão de Cookies',
                    'description' => 'Banner de privacidade interativo com controle granular de preferências de navegação e consentimento.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Gestão Administrativa de Cupons de Desconto',
                    'description' => 'Criação de cupons percentuais e fixos com agendamento, limite de usos e regras por produto.',
                    'audience' => 'admin',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Analytics Nativo de Tráfego & Visitas',
                    'description' => 'Painel com métricas de conversão em tempo real, visitantes únicos, proporção mobile vs desktop e páginas mais populares.',
                    'audience' => 'admin',
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
                    'description' => 'Apresentação de sistemas SaaS, artigos técnicos e especificações detalhadas dos códigos-fonte.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'security',
                    'title' => 'Armazenamento Privado & Links Assinados',
                    'description' => 'Arquivos de venda protegidos contra acesso público direto, com download seguro validado por autenticação.',
                    'audience' => 'all',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Integração Mercado Pago com Webhooks Automáticos',
                    'description' => 'Aprovação em tempo real de PIX e Cartão de Crédito com liberação instantânea de acesso.',
                    'audience' => 'all',
                ],
            ],
        ],
    ],
];
