# PRD — E-commerce Digital KL Tecnologia

## Produto

A plataforma vende produtos digitais para empreendedores e entrega os arquivos de forma privada após a confirmação do pagamento. O cliente pode comprar um item diretamente ou reunir vários produtos no carrinho.

## Perfis

- **Admin:** gerencia produtos, arquivos, categorias, pedidos, cupons, artigos e inscritos da newsletter.
- **Customer:** compra produtos, acompanha pedidos, atualiza seu perfil e baixa arquivos liberados.
- **Visitante:** navega pelo catálogo e blog, mantém carrinho e favoritos locais e cria sua conta durante o checkout.

## Regras de negócio

- Apenas produtos ativos com `file_path` podem aparecer como disponíveis e entrar no checkout.
- Produtos importados sem binário começam inativos.
- O preço do pedido é gravado no momento da compra.
- Cupons precisam existir no banco, estar ativos, vigentes e dentro do limite de utilizações.
- A reserva do cupom e a criação dos pedidos ocorrem na mesma transação.
- Falhas ou rejeições do gateway liberam a reserva do cupom de forma idempotente.
- Pedidos gratuitos são liberados imediatamente; pedidos pagos aguardam confirmação do webhook.
- O retorno do navegador nunca confirma pagamento. Somente o webhook consultado no gateway altera o pedido para `paid`.
- O download exige pedido pago do cliente, Policy autorizada e URL assinada.
- O conteúdo HTML do blog é sanitizado antes de persistir.

## Requisitos operacionais

- PHP 8.3+, Laravel 13, MySQL 8, Blade e Tailwind CSS.
- Binários em `storage/app/digital_products` ou armazenamento privado equivalente.
- Segredos somente em variáveis de ambiente ou gerenciador de segredos.
- `APP_DEBUG=false`, HTTPS e assinatura do webhook em produção.
- CI executando testes, Pint, build, auditorias e migrations MySQL.
