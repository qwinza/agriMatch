<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        if ($request->quantity > $product->stock) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }

        $totalPrice = $product->price * $request->quantity;

        Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Reduce product stock
        $product->decrement('stock', $request->quantity);

        return redirect()->route('products.index')->with('success', 'Order placed successfully.');
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('product')->get();
        return view('orders.index', compact('orders'));
    }
}
