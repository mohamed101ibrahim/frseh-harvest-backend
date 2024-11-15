<?php

namespace App\Http\Controllers;

use App\Http\Resources\TakesResource;
use App\Models\Takes;
use Illuminate\Http\Request;

class TakesController extends Controller
{
    public function index()
    {
        $orders = Takes::with(['order'])->get();
        return response()->json([
            'data' => TakesResource::collection($orders),
            'message' => 'Orders retrieved successfully',
        ], 200);
    }

    public function deleteitemfromTakes($id)
    {
        Takes::destroy($id);
        return response()->json(['message' => 'Item deleted successfully from Card ']);
    }
}
