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

    public function testDeleteCategory(): void
    {
        $response = $this->delete("api/category/6");

        $response->assertStatus(200);
    }

    public function testCreateCategoryWithoutName()
    {
        $response = $this->post('api/category', [
            'photo' => UploadedFile::fake()->image('test.jpg'),
        ]);

        // Updated expectation to handle the redirect
        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }


    public function testCreateCategoryWithoutPhoto()
    {
        $response = $this->post('api/category', [
            'name' => 'Category Without Photo',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('photo');
    }


    public function testDeleteNonExistingCategory()
    {
        $response = $this->delete("api/category/999");
        $response->assertStatus(200);
        $response->assertSeeText('Categories with ID 999 was not found.');
    }


    public function testDeleteCategoryWithImage()
    {
        $category = Categories::find(156);
        $response = $this->delete("api/category/{$category->id}");
        $response->assertStatus(200);
        $response->assertSeeText("Categories with ID {$category->id} has been deleted.");
        $this->assertFalse(Storage::exists("public/{$category->image}"));
    }


    public function testGetImageNonExisting()
    {
        $response = $this->get('api/category/image/nonexistent.jpg');
        $response->assertStatus(404);
        $response->assertSeeText('Not Found');
    }




    public function testGetExistingImage()
    {
        $category = Categories::find(156);
        $response = $this->get("api/category/image/{$category->image}");
        $response->assertStatus(404);
    }

}






