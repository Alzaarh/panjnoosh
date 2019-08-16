<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use App\Jobs\IncProductViewCountJob;
use Illuminate\Support\Facades\Redis;

class ProductsController extends Controller {
    private const REDIS_KEY = 'products_view_count';
    public function __construct() {
        $this->middleware('check.pagination', ['only' => ['index']]);
    }
    private function validateProduct(Request $request)
    {
        $rules = [
            'title' => 'bail|required|string',
            'short_desc' => 'string',
            'thumbnail' => 'bail|required|string',
            'price' => 'bail|numeric|gte:0',
            'off' => 'bail|numeric|gte:0|lte:100',
            'quantity' => 'bail|required|numeric|gte:0',
            'desc' => 'string',
            'category_id' => 'exists:categories,id',
        ];

        return $this->validate($request, $rules);
    }
    //Get all products
    public function index() {
        $products = Product::all();

        $products->category = $products->map(function ($item, $key) {

            return $item->category;
        });

        return $this->ok($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $product->category = $product->category;

        dispatch(new IncProductViewCountJob('product:' . $id));

        return $this->ok($product);
    }

    public function create(Request $request)
    {
        $data = $this->validateProduct($request);

        $product = Product::create($data);

        return $this->created('product created');
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateProduct($request);

        $product = Product::findOrFail($id);

        $product->update($data);

        return $this->ok('product updated');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        Redis::ZREM(self::REDIS_KEY, 'product:' . $id);

        return $this->ok('product deleted');
    }

    public function topProducts()
    {
        $data = [];

        $result = Redis::ZREVRANGE(self::REDIS_KEY, 0, 4, 'WITHSCORES');

        foreach($result as $key => $value)
        {
             $id = explode(':', $key)[1];

             $product = Product::findOrFail($id);

             $object = new \StdClass();
            
             $object->product = $product;

             $object->category = $product->category;
             
             $object->view_count = $value;

             array_push($data, $object);
        }

        return $this->ok($data);
    }
}
