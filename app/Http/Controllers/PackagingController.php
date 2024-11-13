<?php

namespace App\Http\Controllers;

use App\Models\Packaging;
use App\Http\Resources\PackagingResource;
use Illuminate\Http\Request;

class PackagingController extends Controller
{
    public function index()
    {
        return PackagingResource::collection(Packaging::with('items')->get());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        $packaging = Packaging::create($request->all());
        return new PackagingResource($packaging);
    }

    public function show($id)
    {
        $packaging = Packaging::with('items')->findOrFail($id);
        return new PackagingResource($packaging);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string']);
        $packaging = Packaging::findOrFail($id);
        $packaging->update($request->all());
        return new PackagingResource($packaging);
    }

    public function destroy($id)
    {
        Packaging::destroy($id);
        return response()->json(['message' => 'Packaging deleted successfully']);
    }
}

