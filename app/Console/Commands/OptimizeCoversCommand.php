<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeCoversCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-covers {--quality=82 : WebP quality (1-100)} {--max-width=800 : Maximum width in pixels}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otimiza todas as capas em public/covers para o formato moderno WebP reduzindo o peso em até 90%';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('A extensão GD com suporte a WebP não está disponível no PHP.');

            return self::FAILURE;
        }

        $coversDir = public_path('covers');
        if (! File::isDirectory($coversDir)) {
            $this->warn('Diretório public/covers não encontrado.');

            return self::SUCCESS;
        }

        $files = File::files($coversDir);
        $quality = (int) $this->option('quality');
        $maxWidth = (int) $this->option('max-width');
        $totalSavedBytes = 0;
        $processedCount = 0;

        $this->info("Iniciando otimização de imagens em {$coversDir}...");

        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (! in_array($ext, ['png', 'jpg', 'jpeg'])) {
                continue;
            }

            $originalPath = $file->getRealPath();
            $originalSize = filesize($originalPath);
            $filenameWithoutExt = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $webpPath = $coversDir.'/'.$filenameWithoutExt.'.webp';

            $image = match ($ext) {
                'png' => @imagecreatefrompng($originalPath),
                'jpg', 'jpeg' => @imagecreatefromjpeg($originalPath),
                default => null,
            };

            if (! $image) {
                continue;
            }

            $width = imagesx($image);
            $height = imagesy($image);

            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) ($height * ($maxWidth / $width));
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagepalettetotruecolor($image);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            } else {
                imagepalettetotruecolor($image);
                imagealphablending($image, false);
                imagesavealpha($image, true);
            }

            if (imagewebp($image, $webpPath, $quality)) {
                $newSize = filesize($webpPath);
                $saved = $originalSize - $newSize;
                if ($saved > 0) {
                    $totalSavedBytes += $saved;
                }

                imagedestroy($image);
                $processedCount++;

                // Atualizar referências no banco de dados para os produtos
                $oldRelative = 'covers/'.$file->getFilename();
                $newRelative = 'covers/'.$filenameWithoutExt.'.webp';

                Product::where('cover_path', $oldRelative)->update(['cover_path' => $newRelative]);
                Post::where('cover_path', $oldRelative)->update(['cover_path' => $newRelative]);

                // Se gerou um webp com sucesso e ele for menor, podemos remover o original pesado
                if (file_exists($webpPath) && $originalPath !== $webpPath && $newSize < $originalSize) {
                    File::delete($originalPath);
                }
            } else {
                imagedestroy($image);
            }
        }

        $savedMb = round($totalSavedBytes / (1024 * 1024), 2);
        $this->info("Concluído! {$processedCount} imagens convertidas para WebP. Economia de {$savedMb} MB!");

        return self::SUCCESS;
    }
}
