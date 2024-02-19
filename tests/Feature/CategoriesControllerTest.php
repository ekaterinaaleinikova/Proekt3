<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Categories;

class CategoriesControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCategoryCreate()
    {
        Storage::fake('public');
        $photo = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('api/category', [
            'name' => 'New Category',
            'photo' => $photo,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
        ]);

        // // Проверяем, что изображение было сохранено в хранилище
        // Storage::disk('public')->assertExists('image/img/' . $photo->hashName());
    }

    public function testCategoryList()
    {
        Categories::factory()->create(['name' => 'Test Category']);
        $response = $this->get('api/category');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Test Category']);
    }

    // public function testGetImage()
    // {
    //     // Creating a fake category with an image for testing
    //     $category = Categories::factory()->create([
    //         'name' => 'Test Category',
    //         'image' => 'storage/image/test.jpg', // adjust the path based on your storage configuration
    //     ]);

    //     // Sending a request to get the image
    //     $response = $this->get("api/category/image/img/{$category->image}");

    //     // Asserting a successful response
    //     $response->assertStatus(200);

    //     // Asserting that the response has the correct content type header
    //     $response->assertHeader('Content-Type', 'image/img');
    // }
}




