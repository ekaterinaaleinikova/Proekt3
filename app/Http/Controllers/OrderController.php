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

            return response()->json(['message' => 'Новый заказ успешно создан', 'order_id' => $order->id, 'total_price' => $order->total_price]);

        }

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['message' => 'Товар не найден'], 404);
        }

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'count' => $request->count,
        ]);

        return response()->json(['message' => 'Товар успешно добавлен в корзину', 'item' => $item]);
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
        $order = Order::findOrFail($id);
        $orderItems = OrderItem::where('order_id', $order->id)->get();

        return view('orders.show', compact('order', 'orderItems'));
    }
}
