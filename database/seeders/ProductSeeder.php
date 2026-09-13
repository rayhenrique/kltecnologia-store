<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'Kit de Templates para Instagram',
            'Planilha de Controle Financeiro',
            'Pack de Artes para Delivery',
            'E-book Negócio Digital do Zero',
            'Calendário Editorial para Redes Sociais',
        ];

        foreach ($titles as $title) {
            $slug = Str::slug($title);
            $filePath = 'products/demo-'.$slug.'.txt';
            $attributes = Product::factory()->raw([
                'title' => $title,
                'cover_path' => null,
                'file_path' => $filePath,
            ]);
            Storage::disk('digital_products')->put($filePath, "Arquivo demonstrativo local: {$title}\n");
            $product = Product::withTrashed()->where('slug', $slug)->first();

            if ($product === null) {
                Product::create($attributes);

                continue;
            }

            $product->fill($attributes);

            if ($product->trashed()) {
                $product->restore();

                continue;
            }

            $product->save();
        }
    }
}
