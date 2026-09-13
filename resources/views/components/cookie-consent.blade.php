{{-- Componente de Consentimento de Cookies & Privacidade (LGPD - Lei nº 13.709/2018) --}}
<div 
    x-data="{
        showBanner: false,
        showModal: false,
        categories: {
            essential: true, // Sempre obrigatório
            preferences: true,
            analytics: true,
            marketing: false
        },
        init() {
            try {
                const stored = localStorage.getItem('kl_cookie_consent');
                if (!stored) {
                    // Exibe o banner suavemente após um breve atraso
                    setTimeout(() => {
                        this.showBanner = true;
                    }, 600);
                } else {
                    const parsed = JSON.parse(stored);
                    if (parsed && parsed.categories) {
                        this.categories.preferences = !!parsed.categories.preferences;
                        this.categories.analytics = !!parsed.categories.analytics;
                        this.categories.marketing = !!parsed.categories.marketing;
                    }
                }
            } catch (e) {
                this.showBanner = true;
            }

            // Permite reabrir o gerenciador de cookies a qualquer momento
            window.addEventListener('open-cookie-settings', () => {
                this.showBanner = false;
                this.showModal = true;
            });
        },
        acceptAll() {
            this.categories = {
                essential: true,
                preferences: true,
                analytics: true,
                marketing: true
            };
            this.saveConsent('all');
        },
        acceptEssential() {
            this.categories = {
                essential: true,
                preferences: false,
                analytics: false,
                marketing: false
            };
            this.saveConsent('essential_only');
        },
        saveCustom() {
            this.saveConsent('custom');
        },
        saveConsent(type) {
            const consentData = {
                type: type,
                categories: this.categories,
                date: new Date().toISOString(),
                version: '1.0-lgpd'
            };

            try {
                localStorage.setItem('kl_cookie_consent', JSON.stringify(consentData));
            } catch (e) {
                console.error('Erro ao salvar consentimento de cookies:', e);
            }

            this.showBanner = false;
            this.showModal = false;

            window.dispatchEvent(new CustomEvent('cookie-consent-updated', { detail: consentData }));
            window.dispatchEvent(new CustomEvent('toast-message', {
                detail: {
                    message: 'Preferências de privacidade salvas com sucesso! 🛡️',
                    type: 'success'
                }
            }));
        }
    }"
    class="relative z-50"
>
    {{-- 1. Banner Flutuante de Cookies (Aviso Rápido) --}}
    <div 
        x-show="showBanner"
        x-cloak
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-8 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-8 opacity-0"
        class="fixed bottom-4 inset-x-4 sm:inset-x-auto sm:right-6 sm:bottom-6 sm:max-w-xl z-50"
        role="region"
        aria-label="Aviso de Privacidade e Cookies"
    >
        <div class="rounded-2xl border border-slate-700/80 bg-slate-950/95 p-5 sm:p-6 text-slate-200 shadow-2xl backdrop-blur-xl ring-1 ring-white/10">
            <div class="flex items-start gap-4">
                {{-- Ícone LGPD / Cookies --}}
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                {{-- Texto Explicativo --}}
                <div class="flex-1 text-xs sm:text-sm text-slate-300 space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="font-display font-bold text-white text-sm sm:text-base flex items-center gap-1.5">
                            Privacidade & Cookies
                            <span class="inline-flex items-center rounded-md bg-teal-500/20 px-1.5 py-0.5 text-[10px] font-bold text-teal-300 border border-teal-500/30">
                                LGPD
                            </span>
                        </h3>
                    </div>
                    <p class="leading-relaxed text-slate-300">
                        Utilizamos cookies e tecnologias essenciais para garantir o funcionamento seguro da loja, manter seu carrinho e favoritos ativos e aprimorar sua experiência de compra, em total conformidade com a <strong>Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018)</strong>.
                    </p>
                    <p class="text-[11px] sm:text-xs text-slate-400">
                        Você pode aceitar todos, navegar apenas com os necessários ou gerenciar suas opções a qualquer momento. Saiba mais em nossa
                        <a href="{{ route('privacy.index') }}" class="text-teal-400 underline hover:text-teal-300 font-medium">Política de Privacidade</a>.
                    </p>
                </div>
            </div>

            {{-- Botões de Ação do Banner --}}
            <div class="mt-4 pt-3 border-t border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2.5">
                <button 
                    type="button"
                    @click="showModal = true"
                    class="order-3 sm:order-1 px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white transition text-center underline cursor-pointer"
                >
                    Personalizar Preferências
                </button>

                <button 
                    type="button"
                    @click="acceptEssential()"
                    class="order-2 px-3.5 py-2 rounded-xl border border-slate-700 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white text-xs font-semibold transition cursor-pointer text-center"
                >
                    Apenas Essenciais
                </button>

                <button 
                    type="button"
                    @click="acceptAll()"
                    class="order-1 sm:order-3 px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-bold transition shadow-lg shadow-teal-500/20 cursor-pointer text-center"
                >
                    Aceitar Todos
                </button>
            </div>
        </div>
    </div>

    {{-- 2. Modal Detalhado de Configuração de Cookies (LGPD) --}}
    <div 
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cookie-settings-title"
    >
        {{-- Backdrop com desfoque --}}
        <div 
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="showModal = false"
            class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"
        ></div>

        {{-- Caixa da Modal --}}
        <div 
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-2xl rounded-3xl border border-slate-800 bg-slate-900 p-6 sm:p-8 text-slate-200 shadow-2xl z-10 max-h-[90vh] flex flex-col"
        >
            {{-- Header da Modal --}}
            <div class="flex items-center justify-between pb-4 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="grid h-9 w-9 place-items-center rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 id="cookie-settings-title" class="font-display text-lg sm:text-xl font-bold text-white">
                            Central de Preferências de Cookies
                        </h2>
                        <p class="text-xs text-slate-400">
                            Em conformidade com a Lei Geral de Proteção de Dados Pessoais (LGPD)
                        </p>
                    </div>
                </div>

                <button 
                    type="button" 
                    @click="showModal = false"
                    class="grid h-8 w-8 place-items-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition"
                    aria-label="Fechar configurações de cookies"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Conteúdo com Categorias (Scrollável) --}}
            <div class="py-5 space-y-4 overflow-y-auto pr-1 flex-1 text-xs sm:text-sm">
                <p class="text-slate-300 leading-relaxed text-xs">
                    Na <strong>KL Tecnologia</strong>, respeitamos a sua privacidade. Abaixo você pode personalizar quais cookies e armazenamentos locais autoriza durante sua navegação. Os cookies estritamente necessários não podem ser desativados pois são indispensáveis para o funcionamento da plataforma.
                </p>

                {{-- 1. Cookies Essenciais (Sempre Ativos) --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 sm:p-5 space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white text-sm">1. Cookies Estritamente Necessários</span>
                            <span class="rounded-md bg-emerald-500/20 px-2 py-0.5 text-[10px] font-extrabold text-emerald-400 border border-emerald-500/30">
                                Sempre Ativo
                            </span>
                        </div>
                        <span class="text-slate-500 text-xs font-mono">Obrigatório</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Essenciais para a segurança, autenticação de sessão, prevenção contra falsificação de requisições (CSRF), funcionamento do carrinho de compras e processamento de pagamentos criptografados.
                    </p>
                    <div class="pt-1 text-[11px] text-slate-500 font-mono">
                        Exemplos: <code>laravel_session</code>, <code>XSRF-TOKEN</code>, <code>kl_cart</code>
                    </div>
                </div>

                {{-- 2. Preferências & Experiência --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 sm:p-5 space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-white text-sm">2. Cookies de Preferências & Favoritos</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="categories.preferences" 
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                        </label>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Permitem salvar suas preferências visuais e manter sua lista de produtos favoritados memorizada no navegador para fácil consulta posterior.
                    </p>
                    <div class="pt-1 text-[11px] text-slate-500 font-mono">
                        Exemplos: <code>kl_favorites</code>, <code>kl_cookie_consent</code>
                    </div>
                </div>

                {{-- 3. Desempenho & Estatísticas (Analíticos) --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 sm:p-5 space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-white text-sm">3. Estatísticas e Desempenho (Analíticos)</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="categories.analytics" 
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                        </label>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Coletam informações agregadas e anônimas sobre velocidade de carregamento, erros do sistema e páginas mais acessadas para melhoria contínua dos nossos sistemas web.
                    </p>
                </div>

                {{-- 4. Marketing e Comunicação --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-950/60 p-4 sm:p-5 space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-white text-sm">4. Comunicação & Ofertas Exclusivas</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                x-model="categories.marketing" 
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                        </label>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Utilizados caso você deseje receber cupons de desconto personalizados e anúncios sobre novos lançamentos de códigos-fonte e sistemas PHP/Laravel.
                    </p>
                </div>
            </div>

            {{-- Rodapé da Modal --}}
            <div class="pt-4 border-t border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 shrink-0">
                <a 
                    href="{{ route('privacy.index') }}" 
                    class="text-xs text-teal-400 hover:text-teal-300 underline text-center sm:text-left"
                >
                    Ver Política de Privacidade Completa &rarr;
                </a>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <button 
                        type="button"
                        @click="acceptEssential()"
                        class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition cursor-pointer text-center"
                    >
                        Rejeitar Opcionais
                    </button>

                    <button 
                        type="button"
                        @click="saveCustom()"
                        class="px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold transition shadow-md shadow-teal-600/20 cursor-pointer text-center"
                    >
                        Salvar Preferências
                    </button>

                    <button 
                        type="button"
                        @click="acceptAll()"
                        class="px-4 py-2.5 rounded-xl bg-teal-400 hover:bg-teal-300 text-slate-950 text-xs font-extrabold transition shadow-md shadow-teal-400/30 cursor-pointer text-center"
                    >
                        Aceitar Todos
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
