<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProductStorageService
{
    public function create(array $attributes, ?UploadedFile $cover, ?UploadedFile $file = null): Product
    {
        $coverPath = $cover ? $this->storeCover($cover) : null;
        $filePath = $file ? Storage::disk('digital_products')->putFile('', $file) : null;

        try {
            return DB::transaction(fn (): Product => Product::create([
                ...Arr::except($attributes, ['cover', 'file']),
                'cover_path' => $coverPath,
                'file_path' => $filePath,
            ]));
        } catch (Throwable $exception) {
            $this->deleteCover($coverPath);
            if ($filePath) {
                Storage::disk('digital_products')->delete($filePath);
            }
            throw $exception;
        }
    }

    public function update(Product $product, array $attributes, ?UploadedFile $cover, ?UploadedFile $file): Product
    {
        $oldCover = $product->cover_path;
        $oldFile = $product->file_path;
        $newCover = $cover ? $this->storeCover($cover) : null;
        $newFile = $file ? Storage::disk('digital_products')->putFile('', $file) : null;

        try {
            DB::transaction(function () use ($product, $attributes, $newCover, $newFile): void {
                $product->update([
                    ...Arr::except($attributes, ['cover', 'file']),
                    'cover_path' => $newCover ?? $product->cover_path,
                    'file_path' => $newFile ?? $product->file_path,
                ]);
            });
        } catch (Throwable $exception) {
            $this->deleteCover($newCover);
            if ($newFile) {
                Storage::disk('digital_products')->delete($newFile);
            }
            throw $exception;
        }

        if ($newCover) {
            $this->deleteCover($oldCover);
        }
        if ($newFile) {
            $this->deleteDigitalFile($oldFile);
        }

        return $product->refresh();
    }

    public function archive(Product $product): void
    {
        $product->delete();
    }

    private function storeCover(UploadedFile $cover): string
    {
        File::ensureDirectoryExists(public_path('covers'));

        if (function_exists('imagewebp') && in_array(strtolower($cover->getClientOriginalExtension() ?: $cover->extension()), ['jpg', 'jpeg', 'png', 'webp'])) {
            $ext = strtolower($cover->getClientOriginalExtension() ?: $cover->extension());
            $image = match ($ext) {
                'jpg', 'jpeg' => @imagecreatefromjpeg($cover->getRealPath()),
                'png' => @imagecreatefrompng($cover->getRealPath()),
                'webp' => @imagecreatefromwebp($cover->getRealPath()),
                default => null,
            };

            if ($image) {
                $filename = Str::uuid().'.webp';
                $destination = public_path('covers/'.$filename);
                $width = imagesx($image);
                $height = imagesy($image);

                if ($width > 600) {
                    $newWidth = 600;
                    $newHeight = (int) ($height * (600 / $width));
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

                imagewebp($image, $destination, 82);
                imagedestroy($image);

                return 'covers/'.$filename;
            }
        }

        $filename = Str::uuid().'.'.$cover->extension();
        $cover->move(public_path('covers'), $filename);

        return 'covers/'.$filename;
    }

    private function deleteCover(?string $path): void
    {
        if ($path && str_starts_with($path, 'covers/')) {
            File::delete(public_path($path));
        }
    }

    private function deleteDigitalFile(?string $path): void
    {
        if (filled($path)) {
            Storage::disk('digital_products')->delete($path);
        }
    }
}
