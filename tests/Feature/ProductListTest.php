<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Product;

class ProductListTest extends TestCase
{
    //use RefreshDatabase;
    use DatabaseTransactions;

    public function testProductList()
    {
        $imageDirectory = storage_path('storage/image/img');
        if (!is_dir($imageDirectory)) {
            mkdir($imageDirectory, 0755, true);
        }

        $products = Product::factory()->count(3)->create();
        $response = $this->get('api/product');
        $response->assertStatus(200);
        $responseData = $response->json();

        // Проверьте, что количество элементов в ответе соответствует созданным продуктам
        //$this->assertCount(3, $responseData);
    }



}






