# PRD.md (Product Requirements Document)

## 1. Resumo do Produto
**Nome:** E-commerce Digital KL Tecnologia
**Objetivo:** Plataforma de vendas unitárias de produtos digitais (templates, projetos autorais e PLRs) voltada para empreendedores.
**Proposta de Valor:** Compra rápida e entrega imediata e segura de arquivos digitais, sem vazamento de links.

## 2. Matriz de Perfis e Permissões (Roles)
* **Admin:** Acesso ao painel administrativo. Pode gerenciar produtos (criar, editar, inativar, fazer upload de arquivos e capas) e visualizar todas as vendas/pedidos.
* **Customer (Cliente):** Acesso à vitrine pública e à área logada "Meus Downloads". Pode realizar compras e baixar arquivos de pedidos com status `paid`.

## 3. Requisitos Funcionais (Core Features)
* **Módulo de Autenticação:** Registro, login e recuperação de senha (via Laravel Breeze). O checkout exige autenticação prévia.
* **Módulo de Catálogo:** Vitrine pública listando produtos ativos com título, preço, imagem de capa e descrição.
* **Módulo de Checkout:** Geração de pedido no banco local e integração com API do gateway (Mercado Pago) para gerar link/QR Code de pagamento.
* **Módulo de Webhook:** Endpoint POST não autenticado (mas validado por assinatura/token do gateway) para receber atualizações de status de pagamento e atualizar o pedido localmente para `paid`.
* **Módulo de Entrega:** Área do cliente listando compras. Geração de URLs temporárias e assinadas para download do binário via `Storage::download()`, garantindo que o arquivo não seja acessível publicamente.
* **Módulo Administrativo:** CRUD básico de produtos e dashboard simples de pedidos.

## 4. Requisitos Não-Funcionais
* **Segurança de Arquivos:** Os binários dos produtos digitais devem ser armazenados no disco `local` (storage/app) ou S3/Spaces, estritamente fora da pasta `public/`.
* **Idempotência:** O listener do Webhook do gateway deve verificar o status atual do pedido antes de processar a liberação, evitando processamento duplicado.
* **Stack:** PHP 8.2+, Laravel, MySQL 8.0+, Tailwind CSS.