<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; 

class OrderController extends Controller
{

    public function createOrder(Request $request)
    {
        //dd($request);
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // dd($request);
        DB::beginTransaction();

        try {
           
            $order = Order::create([
                'user_id' => $request->user_id,
                'admin_id'=> $request->admin_id,
                'total' => 0, 
                'status' => "Waiting_Review",
            ]);

            $totalWeight = 0;

            // dd($order);
           // dd($request->items);
            foreach ($request->items as $itemData) {
                // dd($itemData['item_id']);
                $item = Item::findOrFail($itemData['item_id']);
                $quantity = $itemData['quantity'];

                $totalWeight += $item->weight * $quantity;

               
               
                // dd($totalWeight);
                DB::table('takes')->insert([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'quantity'=>$quantity ,
                ]);
                // dd(now());
            }

            $order->update(['total' => $totalWeight]);

            DB::commit();
            //dd(new OrderResource($order));
            return response()->json([
                'success' => true,
                'data' => new OrderResource($order),
                'message' => 'Holiday created successfully',
            ], 201);
            
            

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order creation failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $orders = Order::with(['user'])->get();
        return response()->json([
            'data' => OrderResource::collection($orders),
            'message' => 'Orders retrieved successfully',
        ], 200);
    }

    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $order->update(['status' => 'Reviewed']);
        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'total' => 'numeric',
            'status' => 'string',
            'items' => 'array',
            'items.*.id' => 'exists:items,id',
            'items.*.quantity' => 'integer|min:1',
        ]);
    
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        DB::beginTransaction();
    
        try {
            
            $order = Order::findOrFail($id);
    
            $order->update([
                'user_id' => $request->user_id ?? $order->user_id,
                'total' => $request->total ?? $order->total,
                'status' => $request->status ?? $order->status,
            ]);
    
            if ($request->has('items')) {
                DB::table('takes')->where('order_id', $order->id)->delete();
    
                $totalWeight = 0;
    
                foreach ($request->items as $itemData) {
                    $item = Item::findOrFail($itemData['id']);
                    $quantity = $itemData['quantity'];
    
                    $totalWeight += $item->weight * $quantity;
    
                    DB::table('takes')->insert([
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'quantity' => $quantity,
                        'date' => now(),
                    ]);
                }
    
                $order->update(['total' => $totalWeight]);
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'data' => new OrderResource($order),
                'message' => 'Order updated successfully',
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order update failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['message' => 'Order deleted successfully']);
    }

    public function getAllOrderDetails()
    {
        $orders = Order::select(
            'orders.id as order_id',
            'users.name as user_name',
            'users.email as user_email',
            'orders.total as total_weight',
            'orders.status'
        )
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->get();

        $orderDetails = $orders->map(function ($order) {
            $totalItems = DB::table('takes')
                ->where('order_id', $order->order_id)
                ->count();

            return [
                'order_id' => $order->order_id,
                'user_name' => $order->user_name,
                'user_email' => $order->user_email,
                'total_items_taken' => $totalItems,
                'total_weight' => $order->total_weight,
                'order_status' => $order->status,
            ];
        });

        return response()->json($orderDetails);
    }
}

