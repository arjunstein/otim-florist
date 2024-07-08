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
            'categoryName' => 'Bunga papan congratulations',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'categoryName' => 'Bunga papan happy wedding',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'categoryName' => 'Bunga papan duka cita',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'categoryName' => 'Bunga standing',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'categoryName' => 'Hand bouquet',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'categoryName' => 'Bunga meja',
            'created_at' => now(),
        ]);
        DB::table('categories')->insert([
            'categoryName' => 'Bunga salib',
            'created_at' => now(),
        ]);
    }
}
