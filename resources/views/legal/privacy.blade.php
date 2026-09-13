<x-storefront-layout>
    <x-slot:title>Política de Privacidade & LGPD</x-slot:title>

<div class="bg-slate-900 py-12 border-b border-slate-800">
    <div class="page-container">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('storefront.index') }}" class="hover:text-teal-400 transition">Início</a>
            <span>/</span>
            <span class="text-teal-400">Política de Privacidade & LGPD</span>
        </nav>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 text-xs font-bold uppercase tracking-wider mb-3">
                    <span class="h-1.5 w-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                    Em conformidade com a Lei nº 13.709/2018
                </div>
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    Política de Privacidade & Proteção de Dados
                </h1>
                <p class="mt-2 text-sm sm:text-base text-slate-400 max-w-2xl">
                    Transparência total sobre como tratamos, protegemos seus dados pessoais e utilizamos cookies em nossa plataforma de produtos digitais.
                </p>
            </div>

            <div class="shrink-0">
                <button 
                    type="button" 
                    @click="$dispatch('open-cookie-settings')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-teal-500/40 bg-teal-500/10 hover:bg-teal-500/20 text-teal-300 font-bold text-xs sm:text-sm transition shadow-sm cursor-pointer"
                >
                    <svg class="h-4 w-4 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Gerenciar Preferências de Cookies
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-container py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        {{-- Menu Lateral / Sumário --}}
        <div class="lg:col-span-4 sticky top-28 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Sumário da Política</h2>
                <nav class="space-y-2 text-xs font-semibold text-slate-700">
                    <a href="#controlador" class="block hover:text-teal-600 transition">1. Controlador dos Dados</a>
                    <a href="#dados-coletados" class="block hover:text-teal-600 transition">2. Dados Pessoais Coletados</a>
                    <a href="#bases-legais" class="block hover:text-teal-600 transition">3. Bases Legais e Finalidades</a>
                    <a href="#cookies-lgpd" class="block hover:text-teal-600 transition">4. Política de Cookies & Armazenamento</a>
                    <a href="#seguranca" class="block hover:text-teal-600 transition">5. Segurança da Informação & Criptografia</a>
                    <a href="#direitos" class="block hover:text-teal-600 transition">6. Direitos do Titular (Art. 18)</a>
                    <a href="#exclusao" class="block hover:text-teal-600 transition">7. Eliminação e Retenção</a>
                    <a href="#contato" class="block hover:text-teal-600 transition">8. Contato e Encarregado (DPO)</a>
                </nav>
            </div>

            <div class="rounded-2xl border border-teal-200 bg-teal-50/70 p-5 text-teal-950 text-xs space-y-2">
                <span class="font-bold flex items-center gap-1 text-teal-800">
                    🛡️ Compromisso de Privacidade
                </span>
                <p class="text-slate-600 leading-relaxed">
                    A KL Tecnologia não comercializa, não aluga e não compartilha dados com corretores de dados (data brokers). Todos os dados destinam-se exclusivamente ao fornecimento dos softwares adquiridos.
                </p>
            </div>
        </div>

        {{-- Conteúdo Principal --}}
        <div class="lg:col-span-8 space-y-10 text-slate-700 leading-relaxed text-sm">
            {{-- 1. Controlador --}}
            <section id="controlador" class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">1</span>
                    Identificação do Controlador de Dados
                </h2>
                <p>
                    A <strong>KL Tecnologia</strong> atua como Controladora no tratamento dos dados pessoais de seus clientes e visitantes, sendo responsável por definir como e por que os dados são utilizados, zelando pela estrita observância da <strong>Lei Geral de Proteção de Dados Pessoais (LGPD - Lei nº 13.709/2018)</strong>.
                </p>
                <div class="text-xs text-slate-500 font-mono bg-slate-50 p-3 rounded-xl border border-slate-100">
                    Razão Social: KL Tecnologia e Soluções Digitais<br>
                    Website Oficial: {{ url('/') }}<br>
                    Contato DPO / Privacidade: contato@kltecnologia.com.br
                </div>
            </section>

            {{-- 2. Dados Coletados --}}
            <section id="dados-coletados" class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-4">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">2</span>
                    Dados Pessoais Coletados
                </h2>
                <p>
                    Coletamos exclusivamente as informações estritamente necessárias para operacionalizar a compra, emitir licenças e viabilizar a entrega dos arquivos digitais adquiridos:
                </p>
                <ul class="list-disc pl-5 space-y-2 text-xs sm:text-sm">
                    <li><strong>Dados Cadastrais de Clientes:</strong> Nome completo, endereço de e-mail e senha criptografada (hash seguro).</li>
                    <li><strong>Dados Fiscais e de Contato:</strong> CPF (obrigatório para produtos comerciais pagos por exigência fiscal e antifraude) e número de WhatsApp para atendimento e suporte técnico.</li>
                    <li><strong>Histórico de Transações e Downloads:</strong> Registros de pedidos, data de liberação, status de pagamento e histórico de downloads seguros efetuados.</li>
                    <li><strong>Dados de Pagamento (Mercado Pago):</strong> Transações via Pix ou Cartão de Crédito são processadas diretamente no ambiente seguro e certificado PCI-DSS do <strong>Mercado Pago</strong>. A KL Tecnologia <strong>nunca</strong> armazena números de cartões ou códigos CVV em seus bancos de dados.</li>
                </ul>
            </section>

            {{-- 3. Bases Legais --}}
            <section id="bases-legais" class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">3</span>
                    Finalidades e Bases Legais (LGPD Art. 7º)
                </h2>
                <div class="space-y-3 text-xs sm:text-sm">
                    <div class="border-l-2 border-teal-500 pl-3">
                        <strong class="text-slate-900">Execução de Contrato (Art. 7º, V):</strong>
                        <p class="text-slate-600">Para liberar o acesso aos códigos-fonte e sistemas comprados, criar a conta do usuário e permitir o download imediato dos arquivos adquiridos.</p>
                    </div>
                    <div class="border-l-2 border-teal-500 pl-3">
                        <strong class="text-slate-900">Cumprimento de Obrigação Legal ou Regulatória (Art. 7º, II):</strong>
                        <p class="text-slate-600">Para emissão de comprovantes fiscais e guarda de registros exigidos pelo Marco Civil da Internet (Lei nº 12.965/2014).</p>
                    </div>
                    <div class="border-l-2 border-teal-500 pl-3">
                        <strong class="text-slate-900">Legítimo Interesse & Segurança (Art. 7º, IX):</strong>
                        <p class="text-slate-600">Prevenção a ataques cibernéticos, proteção contra fraudes financeiras e aprimoramento da performance do software.</p>
                    </div>
                </div>
            </section>

            {{-- 4. Cookies e Armazenamento Local --}}
            <section id="cookies-lgpd" class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">4</span>
                        Política de Cookies & Armazenamento Local
                    </h2>
                    <button 
                        type="button" 
                        @click="$dispatch('open-cookie-settings')"
                        class="text-xs font-bold text-teal-600 hover:text-teal-700 underline"
                    >
                        Configurar Cookies &rarr;
                    </button>
                </div>
                <p>
                    Utilizamos cookies de sessão e armazenamento local (<code class="bg-slate-100 px-1 py-0.5 rounded text-xs">localStorage</code>) com as seguintes finalidades:
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">Identificador</th>
                                <th class="p-2.5">Tipo</th>
                                <th class="p-2.5">Finalidade</th>
                                <th class="p-2.5">Obrigatoriedade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="p-2.5 font-mono text-slate-900">laravel_session</td>
                                <td class="p-2.5">Sessão</td>
                                <td class="p-2.5">Identificação da sessão segura e autenticação do cliente.</td>
                                <td class="p-2.5 font-semibold text-emerald-700">Essencial (Obrigatório)</td>
                            </tr>
                            <tr>
                                <td class="p-2.5 font-mono text-slate-900">XSRF-TOKEN</td>
                                <td class="p-2.5">Segurança</td>
                                <td class="p-2.5">Proteção contra ataques Cross-Site Request Forgery (CSRF).</td>
                                <td class="p-2.5 font-semibold text-emerald-700">Essencial (Obrigatório)</td>
                            </tr>
                            <tr>
                                <td class="p-2.5 font-mono text-slate-900">kl_cart</td>
                                <td class="p-2.5">Local</td>
                                <td class="p-2.5">Mantém os itens do carrinho enquanto o cliente navega na loja.</td>
                                <td class="p-2.5 font-semibold text-emerald-700">Essencial (Obrigatório)</td>
                            </tr>
                            <tr>
                                <td class="p-2.5 font-mono text-slate-900">kl_favorites</td>
                                <td class="p-2.5">Local</td>
                                <td class="p-2.5">Memoriza a lista de produtos favoritados no coração.</td>
                                <td class="p-2.5 text-slate-500">Preferências (Opcional)</td>
                            </tr>
                            <tr>
                                <td class="p-2.5 font-mono text-slate-900">kl_cookie_consent</td>
                                <td class="p-2.5">Local</td>
                                <td class="p-2.5">Registra a opção do visitante sobre o banner da LGPD.</td>
                                <td class="p-2.5 font-semibold text-emerald-700">Essencial (Obrigatório)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- 5. Segurança --}}
            <section id="seguranca" class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">5</span>
                    Segurança da Informação e Criptografia
                </h2>
                <p>
                    Adotamos salvaguardas técnicas e administrativas rigorosas para proteger seus dados pessoais contra acessos não autorizados, destruição ou perda:
                </p>
                <ul class="list-disc pl-5 space-y-2 text-xs sm:text-sm">
                    <li><strong>Criptografia SSL/TLS de 256 bits</strong> em todo o tráfego HTTP da plataforma.</li>
                    <li><strong>Hashes Criptográficos de Senha:</strong> Senhas nunca são armazenadas em texto puro (utilizamos algoritmos padrão Bcrypt/Argon2).</li>
                    <li><strong>Links Temporários Assinados (Signed URLs):</strong> O download dos produtos comprados ocorre exclusivamente por URLs assinadas com tempo de expiração curto, impedindo vazamento ou indexação pública dos arquivos binários.</li>
                </ul>
            </section>

            {{-- 6. Direitos do Titular --}}
            <section id="direitos" class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">6</span>
                    Direitos do Titular de Dados (LGPD Art. 18)
                </h2>
                <p>
                    Conforme a LGPD, você possui direito a:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <strong>✓ Confirmação e Acesso:</strong> Consultar todos os seus dados e pedidos a qualquer momento no seu painel.
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <strong>✓ Retificação:</strong> Atualizar seus dados cadastrais (nome, WhatsApp, e-mail) diretamente no perfil.
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <strong>✓ Revogação do Consentimento:</strong> Ajustar preferências de cookies pelo banner ou pelo rodapé.
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <strong>✓ Eliminação da Conta:</strong> Solicitar ou executar a exclusão definitiva da conta na área restrita.
                    </div>
                </div>
            </section>

            {{-- 7. Eliminação --}}
            <section id="exclusao" class="panel p-6 sm:p-8 bg-white border border-slate-200/80 rounded-2xl shadow-sm space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-100 text-teal-700 text-xs font-extrabold">7</span>
                    Retenção e Eliminação de Dados
                </h2>
                <p>
                    Os dados são mantidos enquanto você mantiver uma conta ativa conosco para assegurar o download vitalício dos produtos comprados. Caso solicite a exclusão de sua conta via painel de configurações, seus dados pessoais serão desidentificados ou excluídos, ressalvadas as obrigações fiscais de guarda de notas fiscais pelo prazo legal de 5 anos (Código Tributário Nacional).
                </p>
            </section>

            {{-- 8. Contato e DPO --}}
            <section id="contato" class="panel p-6 sm:p-8 bg-gradient-to-r from-slate-900 to-slate-950 text-white rounded-2xl shadow-sm space-y-3 border border-slate-800">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="grid h-6 w-6 place-items-center rounded-lg bg-teal-500/20 text-teal-400 text-xs font-extrabold">8</span>
                    Canal de Contato do Encarregado (DPO)
                </h2>
                <p class="text-slate-300 text-xs sm:text-sm">
                    Para exercer quaisquer de seus direitos de titular ou esclarecer dúvidas sobre esta Política de Privacidade e Cookies, entre em contato diretamente com nosso Encarregado de Proteção de Dados:
                </p>
                <div class="pt-2 flex flex-wrap gap-4 text-xs font-mono text-teal-300">
                    <span class="bg-slate-800/80 px-3 py-1.5 rounded-lg border border-slate-700">
                        ✉️ dpo@kltecnologia.com.br
                    </span>
                    <span class="bg-slate-800/80 px-3 py-1.5 rounded-lg border border-slate-700">
                        📱 WhatsApp: +55 (82) 99630-4742
                    </span>
                </div>
            </section>
        </div>
    </div>
</div>
</x-storefront-layout>
