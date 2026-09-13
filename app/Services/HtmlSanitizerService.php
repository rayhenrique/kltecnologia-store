<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizerService
{
    private readonly HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', storage_path('framework/cache/data'));
        $config->set('HTML.Allowed', implode(',', [
            'p', 'br', 'h2', 'h3', 'h4', 'h5',
            'strong', 'b', 'em', 'i', 'u', 's',
            'ul', 'ol', 'li', 'blockquote',
            'a[href|title|target|rel]',
            'img[src|alt|title|width|height]',
            'pre', 'code', 'hr',
            'table', 'thead', 'tbody', 'tr', 'th', 'td',
        ]));
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'mailto' => true,
        ]);
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('HTML.Nofollow', true);
        $config->set('HTML.TargetBlank', true);

        $this->purifier = new HTMLPurifier($config);
    }

    public function sanitize(string $html): string
    {
        return trim($this->purifier->purify($html));
    }
}
