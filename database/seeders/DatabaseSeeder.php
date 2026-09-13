<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LogicException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Os dados de demonstração só podem ser criados nos ambientes local e testing.');
        }

        $this->call([
            AdminUserSeeder::class,
            ProductSeeder::class,
            CustomerUserSeeder::class,
        ]);
    }
}
