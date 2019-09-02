<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product as ProductResource;
use App\Jobs\IncProductViewCount;
use App\Models\Product;
use App\Models\ProductPicture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'create', 'update', 'delete'
        ]]);

        $this->middleware('admin', ['only' => [
            'create', 'update', 'delete'
        ]]);
    }

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

    public function create(Request $request)
    {
        $input = $this->validateProduct($request);
        
        $product = Product::create($input);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = storage_path() . '/app/';

            $name = rand(10000, 99999) . $product->id . '.' . $request->file('logo')->getClientOriginalExtension();

            $request->file('logo')->move($path, $name);

            $product->logo = 'storage/' . $name;
        }

        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $picture) {
                $path = storage_path() . '/app/';

                $name = rand(10000, 99999) . Date('Y-m-d') . $product->id . '.' . $picture->getClientOriginalExtension();

                $picture->move($path, $name);

                ProductPicture::create(['product_id' => $product->id, 'path' => 'storage/' . $name]);
            }
        }

        $product->save();

        return (new ProductResource($product))->response(201);
    }

    public function update(Request $request, $id)
    {
        $input = $this->validateProduct($request);

        $product = Product::findOrFail($id);
        
        if ($request->input('title')) {
            $product->title = $request->input('title');
        }

        if ($request->input('short_description')) {
            $product->short_description = $request->input('short_description');
        }

        if ($request->input('description')) {
            $product->description = $request->input('description');
        }

        if ($request->hasFile('logo')) {
            if ($product->logo) {
                $path = storage_path() . '/app/';

                $name = rand(10000, 99999) . $product->id . '.' . $request->file('logo')->getClientOriginalExtension();

                unlink($path . str_replace('storage/', '', $product->logo));

                $request->file('logo')->move($path, $name);

                $product->logo = 'storage/' . $name;
            }
        }

        if ($request->input('price')) {
            $product->price = $request->input('price');
        }

        if ($request->input('quantity')) {
            $product->quantity = $request->input('quantity');
        }

        if ($request->input('active')) {
            $product->active = $request->input('active');
        }

        if ($request->input('off')) {
            $product->off = $request->input('off');
        }

        if ($request->input('category_id')) {
            $product->category_id = $request->input('category_id');
        }

        if ($request->file('pictures')) {
            if ($product->pictures) {
                foreach ($product->pictures as $picture) {
                    unlink(storage_path() . '/app/' . str_replace('storage/' , '', $picture->path));
                    
                    $picture->delete();
                }
            }
            foreach ($request->file('pictures') as $picture) {
                $name = rand(10000, 99999) . Date('Y-m-d') . $product->id . '.' . $picture->getClientOriginalExtension();

                $picture->move(storage_path() . '/app/', $name);

                ProductPicture::create(['product_id' => $product->id, 'path' => 'storage/' . $name]);
            } 
        }

        $product->save();
        
        return new ProductResource($product);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['message' => 'product deleted'], 200);
    }

    private function validateProduct($request)
    {
        return $this->validate($request, [
            'title' => 'required|string|max:255',

            'short_description' => 'string|max:255',

            'description' => 'string',

            'logo' => 'file|image|max:1024',

            'price' => 'numeric',

            'quantity' => 'required|numeric',

            'active' => 'boolean',

            'off' => 'numeric|min:1|max:100',

            'category_id' => 'numeric|exists:categories,id',

            'pictures.*' => 'file|image|max:1024'

        ]);
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
