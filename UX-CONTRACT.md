# UX Contract — KL Tecnologia

## Escopo e público

A vitrine atende empreendedores brasileiros que compram arquivos digitais. O painel atende administradores que mantêm o catálogo e acompanham pedidos. Toda a interface usa português do Brasil, BRL e o fuso `America/Fortaleza`.

## Navegação e permissões

- Visitantes acessam vitrine, detalhes, cadastro e login.
- Clientes autenticados acessam `Meus downloads`, perfil e checkout.
- Administradores acessam painel, produtos e pedidos. Links administrativos ficam ocultos para clientes e todas as rotas continuam protegidas no servidor.
- Acesso direto sem permissão retorna uma página 403 dentro da identidade visual.

## Formulários e uploads

- A validação é feita no servidor com Form Requests e erros são exibidos ao lado do campo, com `aria-invalid` e `aria-describedby`.
- Formulários usam `novalidate`; durante o envio, o botão é desabilitado e informa `Enviando…`.
- Alterações não enviadas ativam a proteção nativa de saída. Textareas têm altura estável e não podem ser redimensionadas.
- A capa aceita JPG, PNG ou WebP até 4 MB. O produto aceita ZIP, PDF e arquivos comuns de documentos até 100 MB.
- O nome do arquivo selecionado aparece antes do envio. Arquivos vendidos ficam no disco privado.

## Operações e feedback

- Criação e edição são pessimistas: a tela só confirma sucesso após resposta do servidor.
- Mensagens flash globais comunicam sucesso e erro em região viva acessível.
- Arquivar produto exige modal com título do item, consequência e botões `Cancelar` e `Arquivar produto`.
- Paginação preserva `page` na URL e listagens vazias explicam o próximo passo.
- Checkout cria o pedido antes de abrir o Mercado Pago. Falhas confirmadas marcam o pedido como falho e mantêm o usuário na página.

## Pedidos, webhook e downloads

- O valor do pedido é congelado no momento da compra.
- O webhook valida assinatura, consulta o pagamento na API e não reprocessa pedido já pago.
- O cliente vê apenas os próprios pedidos pagos.
- Cada botão de download usa uma URL assinada com validade de dez minutos. Arquivar um produto não revoga downloads já pagos.

## Responsividade e acessibilidade

- Navegação colapsa em telas estreitas; tabelas têm rolagem horizontal sem ocultar ações.
- Todas as ações têm rótulo textual, foco visível e alvo confortável.
- Modais capturam foco, fecham com Escape e devolvem o foco ao acionador.
- Estados não dependem apenas de cor; badges incluem texto explícito.
