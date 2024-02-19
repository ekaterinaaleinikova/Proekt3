<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Order;

class RouteTest extends TestCase
{
    use DatabaseTransactions;

    public function testListCategories()
    {
        $response = $this->get('api/category');
        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'image',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function testCreateCategory()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('api/category', [
            'name' => 'Test Category',
            'photo' => $file,
        ]);

        $response->assertCreated();
        $imagePath = $response->json('storage/image/img');
        Storage::disk('public')->assertExists($imagePath);

        $category = Categories::find($response->json('id'));
        $this->assertNotNull($category);
        $this->assertEquals('Test Category', $category->name);
    }

    public function testDeleteCategory()
    {
        $category = Categories::factory()->create();
        $response = $this->delete("api/category/{$category->id}");

        $response->assertOk();

        $this->assertNull(Categories::find($category->id));
    }

    public function testCreateProduct()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post("api/product", [
            'name' => 'Test Product',
            'price' => 100,
            'description' => 'Product description',
            'category_id' => 4,
            'photo' => $file,
        ]);

        $response->assertCreated();

        $imagePath = $response->json('storage/image/img');
        Storage::disk('public')->assertExists($imagePath);

        $product = Product::find($response->json('id'));
        $this->assertNotNull($product);
        $this->assertEquals('Test Product', $product->name);
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
        $category_id = 1;
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

    public function testAddItem()
    {
        $product = Product::factory()->create();
        $data = [
            'product_id' => $product->id,
            'count' => 2,
            'product_price' => 10,
        ];

        $response = $this->json('POST', '/orders', $data);

        $response->assertSuccessful()
            ->assertJson([
                'message' => 'Товар успешно добавлен в корзину',
            ]);
    }

    public function testGetAllOrders()
    {
        Order::factory()->create();
        $response = $this->json('GET', '/all-orders');
        $response->assertSuccessful()
            ->assertJsonStructure([
                'orders' => [
                    '*' => [
                        'id',
                        'total_price',
                    ],
                ],
            ]);
    }

    public function testDeleteOrder()
    {
        $order = Order::factory()->create();
        $response = $this->json('DELETE', "/delete-order/{$order->id}");
        $response->assertSuccessful()
            ->assertJson([
                'message' => 'Заказ успешно удален',
            ]);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_items', ['order_id' => $order->id]);
    }
}


