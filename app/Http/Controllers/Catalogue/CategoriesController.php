<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Jobs\IncrementCategoryViewCount;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource;

class CategoriesController extends Controller {

    private const REDIS_KEY = 'categories_view_count';

    public function index(Request $request) {

        return CategoryResource::collection(Category::paginate());
    }

    public function show($id) {

        $category = Category::findOrFail($id);

        dispatch(new IncrementCategoryViewCount($category->id, self::REDIS_KEY));

        return (new CategoryResource($category));
    }

    //Products for a category
    public function indexProducts(Request $request, $id) {

        $category = Category::findOrFail($id);

        dispatch(new IncrementCategoryViewCount($category->id, self::REDIS_KEY));

        $products = $category->products()->paginate();

        foreach($products as $product) {

            $product->category = $category;
        }

        return ProductResource::collection($products);
    }

    //Most viewed categories
    public function mostViewedCategories() {

        $mostViewedCategories = [];

        $mostViewedCategoriesIDs = Redis::ZREVRANGE(self::REDIS_KEY, 0, 9, 'WITHSCORES');

        foreach($mostViewedCategoriesIDs as $categoryID => $categoryView) {

             $category = Category::findOrFail($categoryID);

             $category->view_count = $categoryView;

             array_push($mostViewedCategories, $category);
        }

        return CategoryResource::collection(collect($mostViewedCategories));
    }
}
