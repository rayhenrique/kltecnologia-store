<x-storefront-layout>
    <x-slot:title>Termos de Uso e Licenciamento</x-slot:title>

<div class="bg-slate-900 py-12 border-b border-slate-800">
    <div class="page-container">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition">Início</a>
            <span>/</span>
            <span class="text-teal-400">Termos de Uso e Licenciamento</span>
        </nav>
        
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-wider mb-3">
            Licença Comercial de Software
        </div>
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            Termos de Uso e Licenciamento de Software
        </h1>
        <p class="mt-2 text-sm sm:text-base text-slate-400 max-w-2xl">
            Conheça as regras claras de uso, direitos de licenciamento comercial e condições de entrega dos sistemas e códigos-fonte da KL Tecnologia.
        </p>
    </div>
</div>

<div class="page-container py-12">
    <div class="max-w-4xl mx-auto space-y-8 text-slate-700 leading-relaxed text-sm">
        {{-- 1. Objeto --}}
        <section class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">1</span>
                Objeto e Escopo do Serviço
            </h2>
            <p>
                A <strong>KL Tecnologia</strong> disponibiliza para aquisição produtos digitais compostos por códigos-fonte de sistemas web prontos, scripts e aplicações desenvolvidas primordialmente no ecossistema PHP (Laravel, MySQL, Tailwind CSS e Blade).
            </p>
            <p>
                Ao realizar um pedido pago ou obter um item gratuito em nosso catálogo, o usuário declara ter lido, compreendido e aceito integralmente os presentes Termos de Uso.
            </p>
        </section>

        {{-- 2. Licenciamento --}}
        <section class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">2</span>
                Licença de Uso Comercial Definitiva
            </h2>
            <p>
                Cada produto adquirido confere ao cliente uma <strong>Licença de Uso Comercial Definitiva</strong> (vitalícia), com os seguintes direitos e restrições:
            </p>
            <ul class="list-disc pl-5 space-y-2 text-xs sm:text-sm">
                <li><strong class="text-emerald-700">Permitido:</strong> Customizar o código-fonte livremente, alterar identidade visual, hospedar em servidores próprios ou de seus clientes e utilizar em projetos comerciais lucrativos.</li>
                <li><strong class="text-rose-700">Proibido:</strong> Revender, redistribuir ou compartilhar publicamente os arquivos brutos do código-fonte em marketplaces ou repositórios públicos como produto concorrente direto.</li>
            </ul>
        </section>

        {{-- 3. Entrega e Downloads --}}
        <section class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">3</span>
                Entrega Automática e Imediata
            </h2>
            <p>
                A entrega dos sistemas é 100% digital e automática:
            </p>
            <ul class="list-disc pl-5 space-y-2 text-xs sm:text-sm">
                <li><strong>Produtos Gratuitos:</strong> Acesso e liberação de download instantânea imediatamente após o cadastro e conclusão do pedido.</li>
                <li><strong>Produtos Pagos (Pix):</strong> Liberação instantânea em segundos assim que o Mercado Pago confirma a liquidação bancária.</li>
                <li><strong>Produtos Pagos (Cartão):</strong> Liberação automática após análise e aprovação antifraude da operadora.</li>
                <li>Todos os arquivos adquiridos ficam permanentemente disponíveis na aba <a href="{{ route('customer.downloads') }}" class="text-teal-600 underline font-bold hover:text-teal-700">Meus Downloads & Pedidos</a>.</li>
            </ul>
        </section>

        {{-- 4. Suporte --}}
        <section class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">4</span>
                Garantia e Suporte Técnico
            </h2>
            <p>
                Os sistemas acompanham instruções de instalação, documentação e estrutura completa de banco de dados. Garantimos o funcionamento do código conforme demonstrado nas especificações técnicas de cada produto.
            </p>
            <p class="text-xs text-slate-500">
                O suporte técnico abrange dúvidas de instalação e esclarecimentos sobre o projeto base. Adaptações complexas de regras de negócio customizadas são de responsabilidade do desenvolvedor ou contratante.
            </p>
        </section>

        {{-- 5. Privacidade --}}
        <section class="panel p-6 sm:p-8 bg-slate-50 border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">5</span>
                Privacidade & Proteção de Dados (LGPD)
            </h2>
            <p>
                O tratamento de dados pessoais realizado para cumprimento destes termos respeita rigorosamente a Lei Geral de Proteção de Dados (LGPD). Para mais detalhes sobre cookies e seus direitos como titular, consulte nossa
                <a href="{{ route('privacy.index') }}" class="text-teal-600 underline font-bold hover:text-teal-700">Política de Privacidade & LGPD</a>.
            </p>
        </section>

        {{-- 6. Comunicações e Newsletter --}}
        <section class="panel p-6 sm:p-8 bg-white border border-teal-200/80 rounded-2xl shadow-sm space-y-3">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-500 text-white text-xs font-extrabold">6</span>
                Comunicações, Atualizações de Produtos e Inscrição na Newsletter
            </h2>
            <p>
                Ao concluir um pedido (seja gratuito ou pago) ou criar uma conta na <strong>KL Tecnologia</strong>, você concorda em ser inscrito automaticamente em nosso canal de comunicações e novidades (Newsletter).
            </p>
            <p>
                Este canal é utilizado para enviar notificações sobre o lançamento de novos produtos, códigos-fonte, novos artigos e tutoriais técnicos do blog, bem como atualizações de segurança e correções em sistemas adquiridos.
            </p>
            <p class="text-xs sm:text-sm text-slate-600 bg-teal-50/60 p-4 rounded-xl border border-teal-100">
                <strong class="text-teal-900">Direito de Opt-out (Descadastro em 1 Clique):</strong> Em estrita conformidade com a LGPD e as melhores práticas anti-spam, você pode cancelar sua inscrição a qualquer momento. Todo e-mail promocional ou informativo enviado possui no rodapé um link seguro de cancelamento imediato, sem burocracia.
            </p>
        </section>
    </div>
</div>
</x-storefront-layout>
