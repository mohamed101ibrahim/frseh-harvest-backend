<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::with('items')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $order = Order::create($request->all());
        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total' => 'numeric',
            'status' => 'string',
        ]);

        $order = Order::findOrFail($id);
        $order->update($request->all());
        return new OrderResource($order);
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['message' => 'Order deleted successfully']);
    }
}

