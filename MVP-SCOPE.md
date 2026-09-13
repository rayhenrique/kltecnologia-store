# Escopo entregue

## Incluído

- Autenticação Laravel Breeze com perfis Admin e Customer.
- Vitrine, catálogo, busca, categorias, destaques, carrinho e favoritos.
- Checkout unitário ou com vários itens, incluindo cadastro integrado do cliente.
- Cupons percentuais ou fixos cadastrados pelo administrador, com limites e vigência.
- Integração Mercado Pago e webhook assinado e idempotente.
- Produtos gratuitos e pedidos pagos com entrega no disco privado.
- Área do cliente com links temporários, assinados e autorizados por Policy.
- Painel com produtos, categorias, pedidos, cupons, blog e newsletter.
- Blog com importação e sanitização de HTML.
- E-mails transacionais de boas-vindas, pendência e confirmação.
- Páginas legais, aceite no checkout e preferências de cookies.

## Fora do escopo atual

- Assinaturas e cobrança recorrente.
- Programa de afiliados ou marketplace com múltiplos vendedores.
- Geração de licenças e chaves de ativação.
- Disparo de campanhas por plataformas externas de marketing.

## Critérios técnicos

1. Nenhum produto pode ser vendido sem arquivo privado disponível.
2. Downloads exigem usuário autorizado, pedido pago e assinatura temporária.
3. Webhooks duplicados não repetem a liberação ou o e-mail de confirmação.
4. Cupons não existem como constantes públicas no código e seus limites são protegidos por transação.
5. HTML persistido para exibição pública é sanitizado no servidor.
6. Credenciais permanecem fora do código, documentação e histórico Git.
