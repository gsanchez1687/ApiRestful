<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        User::truncate();
        DB::table('category_product')->truncate();

        $Cantidadusuarios = 200;
        $Cantidadcategorias = 30;
        $Cantidadproductos = 1000;
        $Cantidadtransacciones = 1000;

        
        User::factory()->count($Cantidadusuarios)->create();
        Category::factory()->count($Cantidadcategorias)->create();
        Product::factory()->count($Cantidadproductos)->create()->each(
            function($producto){
                $categorias = Category::all()->random( mt_rand(1,5) )->pluck('id');
                $producto->categories()->attach($categorias);
            }
        );
        Transaction::factory()->count($Cantidadtransacciones)->create();

        
        
    }
}