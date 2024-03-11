<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use App\Models\Product;

class OrderController extends Controller
{
    public function addItem(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            $order = new Order();
            $order->total_price = (float) $request->product_price * $request->count;
            $order->status_id = Status::CREATED;
            $order->save();
        }

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => (float)$product->price,
            'count' => 1,
        ]);

        return response()->json(['message' => 'Товар успешно добавлен в корзину', 'data' => $item]);
    }


    public function getAllOrders()
    {
        $orders = Order::with('items')->get();

        return response()->json(['orders' => $orders]);
    }

    public function deleteOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Заказ не найден'], 404);
        }

        $order->items()->delete();
        $order->delete();

        return response()->json(['message' => 'Заказ успешно удален']);
    }


    public function show($id)
    {
        $order = Order::where('id',$id)->with('items');
        // $orderItems = OrderItem::where('order_id', $order->id)->get();

        return response()->json($order);
    }
}
