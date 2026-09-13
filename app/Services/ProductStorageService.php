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
    public function create(array $attributes, ?UploadedFile $cover, UploadedFile $file): Product
    {
        $coverPath = $cover ? $this->storeCover($cover) : null;
        $filePath = Storage::disk('digital_products')->putFile('', $file);

        try {
            return DB::transaction(fn (): Product => Product::create([
                ...Arr::except($attributes, ['cover', 'file']),
                'cover_path' => $coverPath,
                'file_path' => $filePath,
            ]));
        } catch (Throwable $exception) {
            $this->deleteCover($coverPath);
            Storage::disk('digital_products')->delete($filePath);
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
            Storage::disk('digital_products')->delete($oldFile);
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
}
