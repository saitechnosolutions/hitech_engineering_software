<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductAndBomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $faker = Faker::create();

    // --- PRODUCTS ---
    $categoryIds = DB::table('categories')->pluck('id')->toArray();
    $products = [];

    for ($i = 1; $i <= 500; $i++) {
        $categoryId = $faker->randomElement($categoryIds);

        $products[] = [
            'category_id'   => $categoryId,
            'product_name'  => "Product_$i",
            'product_image' => 'products/' . $faker->unique()->lexify('product_????') . '.jpg',
            'brand'         => $faker->company,
            'bike_model'    => 'Model ' . $faker->bothify('??##'),
            'mrp_price'     => $faker->numberBetween(500, 50000),
            'part_number'   => strtoupper($faker->bothify('PN-#####')),
            'quantity'      => $faker->numberBetween(1, 100),
            'variation'     => $faker->randomElement(['Red', 'Blue', 'Black', 'White', 'Silver']),
            'hsn_code'      => $faker->numberBetween(100000, 999999),
            'stock_qty'     => $faker->numberBetween(0, 50),
            'design_sheet'  => 'designs/' . $faker->unique()->lexify('design_????') . '.pdf',
            'data_sheet'    => 'datasheets/' . $faker->unique()->lexify('datasheet_????') . '.pdf',
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }

    DB::table('products')->insert($products);

    // --- BOM PARTS ---
    $productIds = DB::table('products')->pluck('id')->toArray();
    $boms = [];

    foreach ($productIds as $productId) {
        $bomCount = $faker->numberBetween(7, 12);

        for ($j = 1; $j <= $bomCount; $j++) {
            $boms[] = [
                'product_id'  => $productId,
                'bom_name'    => "BOM_PRODUCT_ID_{$productId}_B{$j}",
                'bom_unit'    => $faker->randomElement(['pcs', 'kg', 'ltr', 'm', 'box']),
                'bom_qty'=> $faker->numberBetween(1, 25),
                'bom_price'   => $faker->numberBetween(50, 5000),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }
    }

    DB::table('b_o_m_parts')->insert($boms);
    }
}