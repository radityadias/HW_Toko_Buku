<?php

namespace Database\Seeders;

use App\Models\BooksModel;
use App\Models\CategoryModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        CategoryModel::create([
            'name' => 'Fantasy',
        ]);

        BooksModel::create([
            'title' => 'Sky',
            'author' => 'YooKsi',
            'price' => 15000,
            'stock' => 19,
            'category_id' => 2,
        ]);
    }
}
