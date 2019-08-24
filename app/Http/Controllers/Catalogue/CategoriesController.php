<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::paginate());
    }
    public function show($id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }
    //Products for a category
    public function indexProducts(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products()->paginate();
        foreach ($products as $product) {
            $product->category = $category;
        }
        return ProductResource::collection($products);
    }
}
