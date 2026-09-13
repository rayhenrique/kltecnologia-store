# DATABASE-SCHEMA.md

## Diagrama de Entidades
* `users` (1) -> (N) `orders`
* `products` (1) -> (N) `orders`

## Estrutura de Tabelas

### `users`
* `id` (bigIncrements)
* `name` (string)
* `email` (string, unique)
* `password` (string)
* `role` (enum: 'admin', 'customer') - *Default: 'customer'*
* `timestamps`

### `products`
* `id` (bigIncrements)
* `title` (string)
* `slug` (string, unique)
* `description` (text)
* `price` (decimal, 10,2) - *Valor em Reais (BRL)*
* `cover_path` (string, nullable) - *Caminho da imagem de capa (pasta public)*
* `file_path` (string) - *Caminho do arquivo digital protegido (pasta storage privada)*
* `is_active` (boolean) - *Default: true*
* `timestamps`
* `softDeletes`

### `orders`
* `id` (bigIncrements)
* `user_id` (foreignId, constrained, cascadeOnDelete)
* `product_id` (foreignId, constrained, restrictOnDelete)
* `gateway_reference` (string, nullable) - *ID da transação no Mercado Pago/Asaas*
* `status` (enum: 'pending', 'paid', 'failed', 'canceled') - *Default: 'pending'*
* `amount` (decimal, 10,2) - *Preço travado no momento da compra*
* `payment_method` (string, nullable) - *'pix', 'credit_card'*
* `timestamps`