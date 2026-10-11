<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Costumer;
use App\Models\Coupon;
use App\Models\Sneaker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Datos de ejemplo usando las imágenes que venían en media/sneakers del proyecto Django.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $nike = Brand::create(['name' => 'Nike']);
        $newBalance = Brand::create(['name' => 'New Balance']);

        $running = Category::create(['name' => 'Running']);
        $casual = Category::create(['name' => 'Casual']);

        $catalogo = [
            [
                'name' => 'Nike Air Max 90', 'brand' => $nike, 'category' => $casual,
                'image' => 'sneakers/nike-air-max-90_ebe8a8.avif',
                'variants' => [
                    ['color' => 'Negro', 'gender' => 'Unisex', 'price' => 2899, 'image' => 'sneakers/variants/air-max-pro_negro_5ba76b.avif'],
                    ['color' => 'Negro/Rojo', 'gender' => 'Man', 'price' => 2999, 'image' => 'sneakers/variants/air-max-pro_negrorojo_ee5dc1.avif'],
                ],
            ],
            [
                'name' => 'Nike Blazer Mid 77 Vintage', 'brand' => $nike, 'category' => $casual,
                'image' => 'sneakers/nike-blazer-mid-77-vintage_d7e1e9.avif',
                'variants' => [
                    ['color' => 'Blanco/Negro', 'gender' => 'Unisex', 'price' => 2299, 'image' => 'sneakers/variants/nike-blazer-mid-77-vintage_blanconegro_39ef31.avif'],
                    ['color' => 'Negro/Vela', 'gender' => 'Woman', 'price' => 2299, 'image' => 'sneakers/variants/nike-blazer-mid-77-vintage_negrovela_0f5dcd.avif'],
                ],
            ],
            [
                'name' => 'Nike Cortez', 'brand' => $nike, 'category' => $casual,
                'image' => 'sneakers/nike-cortez_41a37b.avif',
                'variants' => [
                    ['color' => 'Marrón', 'gender' => 'Man', 'price' => 1899, 'image' => 'sneakers/variants/nike-cortez_marron_6c7126.avif'],
                    ['color' => 'Rosa', 'gender' => 'Woman', 'price' => 1899, 'image' => 'sneakers/variants/nike-cortez_rosa_24bc35.avif'],
                ],
            ],
            [
                'name' => 'Fresh Foam X 1080v14', 'brand' => $newBalance, 'category' => $running,
                'image' => 'sneakers/fresh-foam-x-1080v14_89449f.webp',
                'variants' => [
                    ['color' => 'Hueso', 'gender' => 'Unisex', 'price' => 3799, 'image' => 'sneakers/variants/fresh-foam-x-1080v14_hueso_020dc7.webp'],
                ],
            ],
        ];

        foreach ($catalogo as $item) {
            $sneaker = Sneaker::create([
                'name' => $item['name'],
                'idBrand' => $item['brand']->idBrand,
                'idCategory' => $item['category']->idCategory,
                'description' => "{$item['name']} de {$item['brand']->name}.",
                'estado' => Sneaker::ACTIVE,
                'image' => $item['image'],
            ]);

            foreach ($item['variants'] as $variant) {
                foreach (['24', '25', '26', '27'] as $size) {
                    $sneaker->variants()->create($variant + ['size' => $size, 'stock' => 10]);
                }
            }
        }

        Coupon::create([
            'code' => 'STEPONE10',
            'maxUses' => 100,
            'discountValue' => 100,
            'validFrom' => now()->startOfMonth(),
            'validUntil' => now()->addYear(),
        ]);

        Costumer::create([
            'first_name' => 'Cliente',
            'last_name' => 'Prueba',
            'email' => 'cliente@stepone.test',
            'phone' => '5500000000',
            'rfc' => 'XAXX010101000',
            'CURP' => 'XEXX010101HNEXXXA4',
            'password' => 'password',
        ]);

        // Acceso al panel /admin
        Costumer::create([
            'first_name' => 'Admin',
            'last_name' => 'StepOne',
            'email' => 'admin@stepone.test',
            'phone' => '5500000001',
            'rfc' => 'XAXX010101001',
            'CURP' => 'XEXX010101HNEXXXA5',
            'password' => 'password',
            'is_admin' => true,
        ]);
    }
}
