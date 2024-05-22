<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\News::create([
            'title' => 'Título de la Noticia 1',
            'summary' => 'Resumen de la noticia 1',
            'source' => 'http://fuente1.com',
        ]);

        \App\Models\News::create([
            'title' => 'Título de la Noticia 2',
            'summary' => 'Resumen de la noticia 2',
            'source' => 'http://fuente2.com',
        ]);
    }
}
