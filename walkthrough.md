# Walkthrough — Módulo Blog, Web Scraper PLW & Sidebar do Admin

Implementamos com sucesso todas as solicitações:
1. **Item "Blog" no menu da loja** e criação das páginas públicas `/blog` e `/blog/{post:slug}`.
2. **Web Scraper automático** do blog `https://vip.plwdesign.online/blog`, importando 18 artigos com capas locais, categorias, slugs e conteúdo formatado.
3. **Redesign do Painel Administrativo:** substituição do navbar superior por uma **Sidebar lateral fixa escura**, badges numéricas e menu responsivo.
4. **Módulo Blog no Admin (CRUD completo):** listagem com filtros e busca, criação, edição com prévia de capa e exclusão com proteção via Policy.
5. **Alinhamento do CRUD de Produtos:** inclusão de `category` (com datalist de sugestões) e `version` alinhados com o layout rico da página de detalhes do produto.

---

## 📸 Telas e Recursos Implementados

### 1. Vitrine da Loja — Navbar com "Blog" e Página do Blog (`/blog`)
- Item **Blog** com ícone moderno inserido no menu superior da vitrine e no rodapé.
- Hero Banner escuro com busca por palavras-chave e pílulas de filtros por categoria com contagem em tempo real.
- Grid responsivo de cards com capas 16:9, tempo estimado de leitura calculado automaticamente (`~X min`), data formatada e badge de categoria.

### 2. Leitura de Artigos (`/blog/{post:slug}`)
- Layout de 2 colunas com tipografia rica (`.blog-content`), cabeçalho com badges, data e contador de visualizações.
- Botões de compartilhamento direto (WhatsApp, X/Twitter e Copiar Link com feedback via Alpine.js).
- Card editorial da **KL Tecnologia**.
- Sidebar sticky com banner comercial direcionando para o catálogo e lista de artigos relacionados e recentes.

### 3. Painel Administrativo com Sidebar Lateral Fixa
- Layout dedicado `<x-admin-layout>` com navegação vertical no padrão dark SaaS:
  - **Dashboard:** Métricas e visão geral.
  - **Produtos:** Gerenciamento do catálogo e criação.
  - **Pedidos:** Controle de vendas pagas e pendentes.
  - **Artigos do Blog:** Gestão de postagens e criação de novos artigos.
  - **Navegação Pública:** Atalhos rápidos para "Ver Loja Principal" e "Ver Blog Público".
  - **Perfil & Logout:** Rodapé da sidebar com identificação do usuário autenticado.

### 4. Gestão de Artigos no Admin (`/admin/posts`)
- Listagem paginada com busca textual e filtro dinâmico por categoria.
- Miniaturas das capas, contador de visualizações, badges de status (*Publicado* ou *Rascunho*).
- Formulário com upload de imagem de capa (preview instantâneo), datalist de categorias, tags HTML rápidas (`<h2>`, `<p>`, `<ul>`, `<code>`, `<blockquote>`) e contador de palavras/tempo de leitura em tempo real.

### 5. CRUD de Produtos Atualizado
- Campos de **Categoria** (com datalist e opções prontas) e **Versão do Sistema** (ex: `v2.4.0`) adicionados tanto na criação quanto na edição.
- Suporte a produtos sem arquivo binário obrigatório no momento do cadastro inicial (facilitando a criação de rascunhos ou produtos com upload posterior).

---

## 🧪 Testes Automatizados & Qualidade de Código

Foram criadas suítes completas de testes no Laravel com 100% de aprovação:

```bash
php artisan test
```

- **82 testes executados com sucesso (282 asserções, 0 falhas)**:
  - `Tests\Feature\BlogTest`: listagem pública, filtro de categorias, busca por termo, incremento de visualizações e proteção de rascunhos.
  - `Tests\Feature\AdminPostCrudTest`: controle de acesso por roles, criação com upload de capa, atualização e exclusão de artigos.
  - `Tests\Feature\AdminProductTest` & `Tests\Feature\Models\ProductTest`: validação de produtos e unicidade de slugs com soft deletes.
- **Formatação PSR-12 / Laravel Pint**: 100% aprovado (`vendor/bin/pint --test`).

---

## 🚀 Como Atualizar no Servidor VPS de Produção

Todas as alterações já foram enviadas para o branch `main` no GitHub. Para aplicar as mudanças no seu servidor de produção:

1. Conecte-se via SSH na VPS:
```bash
ssh kltecnologia-store@72.60.142.2
```

2. Acesse a pasta correta da sua aplicação:
```bash
cd /home/kltecnologia-store/htdocs/kltecnologia.com
```

3. Puxe as atualizações do repositório:
```bash
git pull origin main
```

4. Execute as migrations do banco de dados:
```bash
php artisan migrate --force
```

5. Limpe e regenere os caches do Laravel:
```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

*(Opcional)* Se desejar rodar o scraper de artigos diretamente na VPS para baixar os artigos e capas para o servidor de produção:
```bash
php artisan app:scrape-plw-blog --pages=2
```
