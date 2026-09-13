<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\HtmlSanitizerService;
use Illuminate\Console\Command;

class SanitizeBlogContentCommand extends Command
{
    protected $signature = 'app:sanitize-blog-content';

    protected $description = 'Sanitiza o HTML dos artigos já armazenados';

    public function __construct(private readonly HtmlSanitizerService $sanitizer)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $updated = 0;

        Post::query()->chunkById(100, function ($posts) use (&$updated): void {
            foreach ($posts as $post) {
                $sanitized = $this->sanitizer->sanitize($post->content);
                if ($sanitized !== $post->content) {
                    $post->update(['content' => $sanitized]);
                    $updated++;
                }
            }
        });

        $this->info("Artigos sanitizados: {$updated}.");

        return self::SUCCESS;
    }
}
