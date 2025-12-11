<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // $products = json_decode(file_get_contents(database_path('seeders/productCatalog.json')), true);
        $products = json_decode(file_get_contents(database_path('seeders/products.json')), true);

        foreach ($products as $product) {
            // $product["id"] = $product["product_id"];
            // unset($product["product_id"]);
            Product::create(
                collect($product)->only((new \App\Models\Product)->getFillable())->toArray()
            );
        }
    }
}
