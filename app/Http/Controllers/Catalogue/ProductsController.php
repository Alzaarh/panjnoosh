<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Request;
use App\Jobs\IncProductViewCountJob;
use Illuminate\Support\Facades\Redis;

class ProductsController extends Controller {

    private const REDIS_KEY = 'products_view_count';

    public function index() {
        
        $products = Product::paginate();

        foreach($products as $product) {

            $product->category = $product->category;
        }

        return ProductResource::collection($products);
    }

    public function show($id) {

        $product = Product::findOrFail($id);

        $product->category = $product->category;

        dispatch(new IncProductViewCountJob('product:' . $id));

        return new ProductResource($product);
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
