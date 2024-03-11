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

    
    public function testProductsByNonExistingCategory()
    {
        $category_id = 999; // Предположим, что 999 - это ID несуществующей категории
        $response = $this->getJson("api/product/{$category_id}");
    
        $response->assertStatus(404); // Обновим ожидаемый статус кода до 404
        $response->assertJson([
            'message' => "Category with ID $category_id does not exist."
        ]);
    }
    


    public function testGetImage()
    {
        $product = Product::factory()->create([
            'image' => 'storage/image/img/test.jpg',
        ]);

        $response = $this->get('/product/image/' . basename($product->image));

        $response->assertOk()
            ->assertHeader('Content-Type', 'image/img');
    }

    public function testCreateProductWithoutPrice()
    {
        $response = $this->post("api/product", [
            'name' => 'Test Product',
            'description' => 'Test description',
            'category_id' => 5,
            'photo' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $response->assertSessionHasErrors('price');
    }

    // public function testCreateProductWithoutCategory()
    // {
    //     $response = $this->post("api/product", [
    //         'name' => 'Test Product',
    //         'price' => 100,
    //         'description' => 'Test description',
    //         'photo' => UploadedFile::fake()->image('test.jpg'),
    //         'category_id' => 4
    //     ]);

    //     $response->assertSessionHasErrors('category_id');
    // }

    // public function testCreateProductWithInvalidCategory()
    // {
    //     $response = $this->post("api/product", [
    //         'name' => 'Test Product',
    //         'price' => 100,
    //         'description' => 'Test description',
    //         'category_id' => 999, // Assuming 999 is an invalid category ID
    //         'photo' => UploadedFile::fake()->image('test.jpg'),
    //     ]);

    //     $response->assertSessionHasErrors('category_id');
    // }

    public function testDeleteProductWithImage()
    {
        $product = Product::factory()->create();
        $response = $this->delete("api/product/{$product->id}");
        $response->assertOk();
        $this->assertNull(Product::find($product->id));
        $this->assertFalse(Storage::exists("public/{$product->image}"));
    }

    public function testDeleteProductWithInvalidId()
    {
        $response = $this->delete("api/product/999"); // Assuming 999 is an invalid product ID
        $response->assertNotFound();
    }

    public function testGetImageForNonExistingProduct()
    {
        $response = $this->get('api/product/image/nonexistent.jpg');
        $response->assertNotFound();
    }

    // public function testGetImageForProductWithoutImage()
    // {
    //     $product = Product::factory()->create(['image' => null]);
    //     $response = $this->get("api/product/image/{$product->image}");
    //     $response->assertNotFound();
    // }

    public function testGetImageForProductWithInvalidImage()
    {
        $response = $this->get('api/product/image/invalid.jpg'); // Assuming invalid.jpg does not exist
        $response->assertNotFound();
    }

    public function testGetImageForProductWithValidImage()
    {
        $product = Product::factory()->create(['image' => 'storage/image/img/test.jpg']);
        $response = $this->get("api/product/image/{$product->image}");
        $response->assertOk()->assertHeader('Content-Type', 'image/img');
    }

    public function testGetImageForProductWithInvalidExtension()
    {
        $product = Product::factory()->create(['image' => 'storage/image/img/test.txt']);
        $response = $this->get("api/product/image/{$product->image}");
        $response->assertNotFound();
    }
    

}
