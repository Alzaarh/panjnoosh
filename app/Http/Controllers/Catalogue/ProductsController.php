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
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;

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
        
        if ($request->input('logo')) {
            $this->uploadLogo($request->input('logo'), $product);
        }

        if ($request->input('pictures')) {
            $this->uploadPictures($request->input('pictures'), $product);
        }

        $product->save();

        return (new ProductResource($product))->response(201);
    }

    public function update(Request $request, $id)
    {
        $input = $this->validateProduct($request);

        $product = Product::findOrFail($id);

        $product->update($input);

        if ($request->input('logo')) {
            if ($product->logo) {
                unlink(storage_path() . '/app/' . str_replace('storage/', '', $product->logo));
            }

            $this->uploadLogo($request->input('logo'), $product);
        }

        if ($request->input('pictures')) {
            if ($product->pictures) {
                foreach ($product->pictures as $picture) {
                    unlink(storage_path() . '/app/' . str_replace('storage/' , '', $picture->path));
                    
                    $picture->delete();
                }
            }

            $this->uploadPictures($request->input('pictures'), $product);
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

            'logo' => 'string',

            'price' => 'numeric',

            'quantity' => 'required|numeric',

            'active' => 'boolean',

            'off' => 'numeric|min:1|max:100',

            'category_id' => 'numeric|exists:categories,id',

            'pictures' => 'array',

            'pictures.*' => 'string'
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

    private function uploadLogo($logo, $product)
    {
        $decoder = new Base64ImageDecoder($logo,
            $allowedFormats = ['jpeg', 'png', 'jpg']);

    
        $name = time() . 'logo-' . $product->id . '.' . $decoder->getFormat();

        file_put_contents(storage_path() . '/app/' . $name, $decoder->getDecodedContent());

        $product->logo = 'storage/' . $name;   
    }

    private function uploadPictures($pictures, $product)
    {
        foreach ($pictures as $picture) {
            $decoder = new Base64ImageDecoder($picture,
                $allowedFormats = ['jpeg', 'png', 'jpg']);

            $name = time() . 'picture-' . $product->id . '.' . $decoder->getFormat();

            file_put_contents(storage_path() . '/app/' . $name, $decoder->getDecodedContent());

            ProductPicture::create(['product_id' => $product->id, 'path' => 'storage/' . $name]);
        }
    }
}
