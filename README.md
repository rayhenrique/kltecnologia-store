# KL Tecnologia Store

E-commerce de produtos digitais construído com Laravel 13, PHP 8.3+, MySQL, Blade, Tailwind CSS e Alpine.js.

O sistema oferece catálogo, carrinho, favoritos, checkout com Mercado Pago, cupons cadastrados no painel, entrega privada de arquivos, blog, newsletter e administração de produtos, pedidos e conteúdo.

## Requisitos

- PHP 8.3 ou superior
- Composer 2
- MySQL 8 ou MariaDB compatível
- Node.js 20 ou superior

## Instalação local

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Configure a conexão MySQL, o SMTP e as credenciais do Mercado Pago somente no arquivo `.env`. Esse arquivo não deve ser versionado.

## Administrador

O projeto não cria administrador com credenciais conhecidas. Informe o e-mail e digite a senha forte no prompt oculto:

```bash
php artisan app:create-admin admin@seu-dominio.com --name="Administrador"
```

Em automações, injete a senha pelo gerenciador de segredos do ambiente na variável `ADMIN_PASSWORD` e execute o mesmo comando. É possível indicar outro nome de variável com `--password-env`.

## Arquitetura

- `app/Http/Requests`: validação e autorização de entradas.
- `app/Policies`: autorização de recursos administrativos e downloads.
- `app/Services/CheckoutService.php`: criação transacional de clientes, pedidos e reservas de cupom.
- `app/Services/MercadoPagoService.php`: comunicação com a API de pagamentos.
- `app/Services/WebhookService.php`: confirmação idempotente de pagamentos.
- `app/Services/ProductStorageService.php`: upload e substituição segura de arquivos.
- `app/Services/HtmlSanitizerService.php`: sanitização do HTML publicado no blog.

Os binários ficam em `storage/app/digital_products`, fora do diretório público. O download exige autenticação, Policy, pedido pago e URL assinada.

Produtos sem arquivo ficam indisponíveis para venda. O scraper importa esses produtos como inativos, permitindo que o administrador envie o binário antes da ativação.

## Rotas principais

| Método | Rota | Finalidade |
|---|---|---|
| `GET` | `/` | Vitrine |
| `GET` | `/catalogo` | Catálogo e filtros |
| `GET` | `/carrinho` | Carrinho local |
| `GET` | `/favoritos` | Favoritos locais |
| `GET` | `/checkout` | Identificação e resumo |
| `POST` | `/checkout/processar` | Criação do pedido e preferência |
| `POST` | `/webhooks/mercado-pago` | Atualização assinada do pagamento |
| `GET` | `/downloads/{order}` | Download privado e assinado |
| `GET` | `/admin` | Painel administrativo |
| `resource` | `/admin/products` | Produtos |
| `resource` | `/admin/orders` | Pedidos |
| `resource` | `/admin/coupons` | Cupons |
| `resource` | `/admin/posts` | Blog |

## Qualidade

```bash
php artisan test
vendor/bin/pint --test
npm run build
composer audit --locked
npm audit --audit-level=moderate
```

O workflow de CI executa testes, formatação, build, auditorias e valida uma migração limpa no MySQL.

## Produção

Use o passo a passo de [`DEPLOY.md`](DEPLOY.md). Antes de liberar o checkout, confirme:

- `APP_ENV=production` e `APP_DEBUG=false`;
- HTTPS ativo;
- webhook do Mercado Pago configurado com assinatura;
- filas e scheduler ativos;
- todos os produtos ativos com arquivo privado disponível;
- nenhuma credencial presente no código ou no histórico Git.
