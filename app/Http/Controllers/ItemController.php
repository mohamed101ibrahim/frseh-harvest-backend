<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = ItemResource::collection(Item::with(['category', 'packagings'])->get());
        return response()->json($items, 200, [], JSON_PRETTY_PRINT);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'weight' => 'required|integer',
            'season' => 'required|string',
            'size' => 'required|string',
            'type' => 'required|string',
            'age' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $item = Item::create($request->all());
        return new ItemResource($item);
    }

    public function show($id)
    {
        $item = Item::with(['category', 'packagings'])->findOrFail($id);
        // dd($item);
        return response()->json($item, 200, [], JSON_PRETTY_PRINT);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
            'weight' => 'integer',
            'season' => 'string',
            'size' => 'string',
            'type' => 'string',
            'age' => 'string',
            'category_id' => 'exists:categories,id',
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->all());
        return new ItemResource($item);
    }

    public function destroy($id)
    {
        Item::destroy($id);
        return response()->json(['message' => 'Item deleted successfully']);
    }
}

