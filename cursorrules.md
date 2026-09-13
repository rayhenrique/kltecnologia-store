# .cursorrules

# Regras de Desenvolvimento - Laravel & MySQL

1. **Stack e Convenções:**
   - Utilize PHP 8.2+ e convenções PSR-12.
   - Respeite a estrutura do Laravel 13.x.
   - Use formatação plural e snake_case para tabelas de banco de dados, camelCase para variáveis e PascalCase para Classes/Models.

2. **Arquitetura e Clean Code:**
   - NUNCA coloque regras de negócio dentro de rotas (routes/web.php).
   - Use Form Requests (`php artisan make:request`) para TODA validação de input de formulário e API.
   - Mantenha as Controllers magras. Se a lógica de integração com gateway ou upload for complexa, extraia para uma classe Service (ex: `app/Services/PaymentService.php`).
   - Proteja chaves estrangeiras com `$table->foreignId('...')->constrained()->onDelete('...')`.

3. **Segurança:**
   - Jamais confie no input do usuário. Use proteção de Mass Assignment via `$fillable` nos Models.
   - O armazenamento dos arquivos dos produtos (binários) DEVE ocorrer no disco local ou S3, inacessível publicamente via URL direta. Use `Storage::download()` com validação de policy/autorização.

4. **Frontend:**
   - Utilize Blade components com Tailwind CSS. 
   - Mantenha o design minimalista, estilo painel SaaS.

5. **Fluxo de Trabalho:**
   - Leia o arquivo `TASKS.md` antes de começar.
   - Sempre que completar uma tarefa principal, atualize ativamente o arquivo `TASKS.md` marcando o checkbox `[x]`.