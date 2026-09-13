# MVP-SCOPE.md

## INCLUSO NO MVP (Must Have)
* Autenticação completa via Laravel Breeze (Admin / Customer).
* Vitrine pública de produtos digitais da KL Tecnologia.
* Checkout direto (unitário) integrado via API REST com Mercado Pago (ou Asaas).
* Webhook receptor de status de pagamento (transição Pending -> Paid).
* Área do cliente para acesso seguro a links de download das compras realizadas.
* Proteção estrita dos arquivos em diretório não-público (`storage`), limitando o download apenas a usuários logados com pedido aprovado.
* Gestão básica administrativa (CRUD de produtos e visualização de pedidos).

## DESCARTADO DO MVP (Future Scope / Out of Scope)
* Carrinho de compras complexo com adição de múltiplos itens.
* Modelo de assinaturas / Clube VIP (billing recorrente).
* Programa de afiliados / sistema multi-vendedor.
* Geração dinâmica de licenças de software ou ativação de chaves.
* Integrações de e-mail marketing externo (Mautic/ActiveCampaign) na finalização de compra.

## Métricas de Sucesso Técnico
1. Zero vazamento de URLs reais dos arquivos de produto.
2. Atualização de status de pedido ocorrendo em menos de 5 segundos após confirmação via Webhook do gateway.
3. Tratamento robusto de Webhooks duplicados (idempotência implementada corretamente).