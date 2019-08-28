<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product as ProductResource;
use App\Jobs\IncProductViewCount;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('title', 'like', '%' . $request->query('search') . '%')
            ->paginate();

        foreach ($products as $product) {
            $product->category = $product->category;
        }

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $product->category = $product->category;

        $this->dispatchNow(new IncProductViewCount($product->id));

        return new ProductResource($product);
    }

    public function topProducts(Request $request)
    {
        $this->validateQueryForTopProducts($request);

        $period = $request->query('period');

        $filterBy = $request->query('filter_by');

        $topProducts = [];

        if ($filterBy === 'views') {

            if ($period === 'day') {

                $topProducts = Redis::ZREVRANGE(Carbon::now()->toDateString(), 0, 9, 'WITHSCORES');
            } elseif ($period === 'month') {

                $topProducts = Redis::ZREVRANGE(Carbon::now()->format('Y-m'), 0, 9, 'WITHSCORES');
            } elseif ($period === 'year') {

                $topProducts = Redis::ZREVRANGE(Carbon::now()->format('Y'), 0, 9, 'WITHSCORES');
            }
        } elseif ($filterBy === 'sales') {

            //Todo
        }

        $topProductsCollection = Product::findOrFail(collect($topProducts)->keys()->toArray());

        foreach ($topProductsCollection as $product) {

            $product->views = $topProducts[$product->id];
        }

        return ProductResource::collection($topProductsCollection);
    }
    private function validateQueryForTopProducts(Request $request)
    {
        $this->validate($request, [
            'filter_by' => 'required|in:views,sales',
            'period' => 'required|in:day,month,year',
        ], [
            'filter_by.*' => 'فیلتر نامعتبر',
            'period.*' => 'زمان نامعتبر',
        ]);
    }

}
