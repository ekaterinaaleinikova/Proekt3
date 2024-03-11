<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Status;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    // public function testAddItem()
    // {
    //     $product = Product::factory()->create();
    //     $response = $this->post('api/orders', [
    //         'product_id' => $product->id,
    //         'count' => 1,
    //     ]);

    //     $response->assertSuccessful();
    //     $response->assertJson(['message' => 'Новый заказ успешно создан']);

    //     $response->assertJson([
    //         'message' => 'Новый заказ успешно создан',
    //         'order_id' => 35,
    //         'total_price' => 0,
    //     ]);

    // }

    public function testGetAllOrders()
    {
        $response = $this->get('api/all-orders');
        $response->assertSuccessful();
        $response->assertJsonStructure(['orders']);
    }

    public function testDeleteOrder()
    {
        $order = Order::factory()->create();
        $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);

        $response = $this->delete("api/delete-order/{$order->id}");

        $response->assertSuccessful();
        $response->assertJson(['message' => 'Заказ успешно удален']);
    }
}
