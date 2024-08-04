<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'category_name' => 'Bunga papan selamat dan sukses',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Bunga papan congratulations',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Bunga papan happy wedding',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Bunga papan dukacita',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Bunga standing',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Hand bouquet',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Bunga meja',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Bunga salib',
            'created_at' => now(),
        ]);
    }
}
