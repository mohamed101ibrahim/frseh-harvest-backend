<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::with('items')->get());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        $category = Category::create($request->all());
        return new CategoryResource($category);
    }

    public function show($id)
    {
        $category = Category::with('items')->findOrFail($id);
        return new CategoryResource($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string']);
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
