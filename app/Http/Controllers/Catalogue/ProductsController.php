<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;

class ProductsController extends Controller
{
    private const REDIS_KEY = 'products';

    use RedisTrait, ResponseTrait;

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
        $result = $this->setExists(self::REDIS_KEY);

        if($result)
        {
            return $this->ok($result);
        }

        $products = Product::all();

        return $this->createSet(self::REDIS_KEY, $products);
    }

    public function show($id)
    {
        $result = $this->hashExists(self::REDIS_KEY . ':' . $id);

        if($result)
        {
            $this->sSetIncr(self::REDIS_KEY . '_count', self::REDIS_KEY . ':' . $id);

            return $this->ok($result);
        }

        $product = Product::findOrFail($id);

        $this->createHash(self::REDIS_KEY . ':' . $id, $product);

        $this->sSetIncr(self::REDIS_KEY . '_count', self::REDIS_KEY . ':' . $id);

        return $this->ok($product);
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
