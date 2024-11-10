<?php

namespace Database\Seeders;

use App\Models\BooksModel;
use App\Models\CategoryModel;
use App\Models\CustomersModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        $genres = [
            ['name' => 'Fantasy'],
            ['name' => 'Isekai'],
            ['name' => 'Action'],
            ['name' => 'Comedy'],
            ['name' => 'Romance'],
        ];

        $books = [
            [
                'title' => 'Majnun',
                'author' => 'Anton Kurnia',
                'price' => 70000,
                'stock' => 20,
                'category_id' => 1,
            ],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'price' => 85000,
                'stock' => 15,
                'category_id' => 2,
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'price' => 90000,
                'stock' => 25,
                'category_id' => 3,
            ],
            [
                'title' => 'Supernova: Ksatria, Putri, dan Bintang Jatuh',
                'author' => 'Dewi Lestari',
                'price' => 95000,
                'stock' => 10,
                'category_id' => 4,
            ],
            [
                'title' => 'Ayat-Ayat Cinta',
                'author' => 'Habiburrahman El Shirazy',
                'price' => 80000,
                'stock' => 30,
                'category_id' => 5,
            ],
        ];

        $users = [
            ['name' => 'Yoga'],
            ['name' => 'Bayu'],
            ['name' => 'Desi'],
            ['name' => 'Adam'],
            ['name' => 'Abdul'],
        ];

        // Memasukkan data ke tabel di database
        CustomersModel::insert($users);
        CategoryModel::insert($genres);
        BooksModel::insert($books);
    }
}
