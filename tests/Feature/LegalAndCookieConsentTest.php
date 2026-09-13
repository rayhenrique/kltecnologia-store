<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalAndCookieConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_includes_lgpd_cookie_consent_banner_and_controls(): void
    {
        $response = $this->get(route('storefront.index'));

        $response->assertOk()
            ->assertSee('Privacidade', false)
            ->assertSee('LGPD')
            ->assertSee('Aceitar Todos')
            ->assertSee('Apenas Essenciais')
            ->assertSee('Personalizar Preferências')
            ->assertSee('Central de Preferências de Cookies')
            ->assertSee(route('privacy.index'));
    }

    public function test_privacy_policy_page_can_be_rendered(): void
    {
        $response = $this->get(route('privacy.index'));

        $response->assertOk()
            ->assertSee('Política de Privacidade', false)
            ->assertSee('Lei nº 13.709/2018')
            ->assertSee('Identificação do Controlador de Dados')
            ->assertSee('Direitos do Titular de Dados', false)
            ->assertSee('laravel_session')
            ->assertSee('kl_cookie_consent')
            ->assertSee('dpo@kltecnologia.com.br');
    }

    public function test_terms_of_use_page_can_be_rendered(): void
    {
        $response = $this->get(route('terms.index'));

        $response->assertOk()
            ->assertSee('Termos de Uso e Licenciamento de Software')
            ->assertSee('Licença de Uso Comercial Definitiva')
            ->assertSee('Entrega Automática e Imediata')
            ->assertSee(route('customer.downloads'));
    }

    public function test_storefront_footer_links_to_legal_pages_and_cookie_preferences(): void
    {
        $response = $this->get(route('storefront.index'));

        $response->assertOk()
            ->assertSee(route('terms.index'))
            ->assertSee(route('privacy.index'))
            ->assertSee('Preferências de Cookies');
    }
}
