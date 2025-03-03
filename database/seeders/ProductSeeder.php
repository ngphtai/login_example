<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 30; $i++) {
            DB::table('roducts')->insert([
                'name' => Str::random(10),
                'slug' => Str::random(10),
                'quantity' => Str::random(10),
                'price' => Str::random(10),
                'description'=> Str::random(30),
            ]);
        }
    }
}