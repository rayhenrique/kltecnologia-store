<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizerService;
use Tests\TestCase;

class HtmlSanitizerServiceTest extends TestCase
{
    public function test_it_removes_executable_html_and_keeps_safe_formatting(): void
    {
        $html = '<p onclick="alert(1)"><strong>Conteúdo</strong>'
            .'<a href="javascript:alert(1)" target="_blank">link</a>'
            .'<img src="https://example.com/image.jpg" onerror="alert(1)">'
            .'<script>alert(1)</script></p>';

        $sanitized = app(HtmlSanitizerService::class)->sanitize($html);

        $this->assertStringContainsString('<strong>Conteúdo</strong>', $sanitized);
        $this->assertStringContainsString('https://example.com/image.jpg', $sanitized);
        $this->assertStringNotContainsString('onclick', $sanitized);
        $this->assertStringNotContainsString('onerror', $sanitized);
        $this->assertStringNotContainsString('javascript:', $sanitized);
        $this->assertStringNotContainsString('<script', $sanitized);
    }
}
