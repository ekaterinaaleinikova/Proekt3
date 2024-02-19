<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Product;

class ProductsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateProduct()
{
    Storage::fake('public'); 

    $file = UploadedFile::fake()->image('test.jpg');

    $response = $this->post("api/product", [
        'name' => 'Test Product',
        'price' => 100,
        'description' => 'Test description',
        'category_id' => 5,
        'photo' => $file,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('product', [
        'name' => 'Test Product',
    ]);

    // // Проверяем, что изображение было сохранено в хранилище
    // Storage::disk('public')->assertExists('image/img/' . $photo->hashName());
}


    

    public function testDeleteProduct()
    {
        $product = Product::factory()->create();
        $response = $this->delete("api/product/{$product->id}");
        $response->assertOk();
        $this->assertNull(Product::find($product->id));
    }

    public function testProductsByCategory()
{
    $category_id = 4;
    Product::factory()->create(['category_id' => $category_id]);
    Product::factory()->create(['category_id' => $category_id]);

    $response = $this->getJson("api/product/{$category_id}");

    $response->assertOk()
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'price',
                'description',
                'category_id',
                'image',
                'created_at',
                'updated_at',
            ],
        ]);
}


    // public function testGetImage()
    // {
    //     // Создайте фейковый продукт для теста
    //     $product = Product::factory()->create([
    //         'image' => 'storage/image/img/test.jpg', // Указываете реальный путь к изображению
    //     ]);

    //     $response = $this->get('/products/get-image/' . basename($product->image));

    //     $response->assertOk()
    //         ->assertHeader('Content-Type', 'image/img');
    // }
}
