<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private function validateProduct(Request $request)
    {
        $rules = [
            'title' => 'bail|required|string',
            'short_desc' => 'string',
            'thumbnail' => 'bail|required|string',
            'price' => 'bail|numeric|gte:0',
            'off' => 'bail|numeric|gte:0|lte:100',
            'desc' => 'string',
            'category_id' => 'exists:categories,id',
        ];

        return $this->validate($request, $rules);
    }

    public function index()
    {
        return response()->json(['data' => Product::all()], 200);
    }

    public function show($id)
    {
        return response()->json(['data' => Product::findOrFail($id)], 200);
    }

    public function create(Request $request)
    {
        $data = $this->validateProduct($request);

        Product::create($data);

        return response()->json(['data' => 'product created'], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $this->validateProduct($request);

        $product->update($data);

        return response()->json(['data' => 'product updated'], 200);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['data' => 'category deleted'], 200);
    }
}
