<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Categories;
use Illuminate\Support\Facades\Storage;

class CategoryDeleteTest extends TestCase
{

    use DatabaseTransactions;

    public function testDeleteCategory(): void
    {
        $response = $this->delete("api/category/6");

        $response->assertStatus(200);
    }



}
