<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['only' => [
            'create',
            'update',
            'delete'
            ]
        ]);

        $this->middleware('admin', ['only' => [
            'create', 
            'update', 
            'delete'
            ]
        ]);
    }

    public function index() {
        return CategoryResource::collection(Category::paginate());
    }

    public function show($id) {
        return new CategoryResource(Category::findOrFail($id));
    }

    //Products for a category
    public function indexProducts(Request $request, $id) {
        $category = Category::findOrFail($id);
        $products = $category->products()->paginate();
        foreach ($products as $product) {
            $product->category = $category;
        }
        return ProductResource::collection($products);
    }

    public function create(Request $request) {
        $input = $this->validateCategory($request);

        $category = Category::create($input);

        return (new CategoryResource($category))->response(201);
    }

    public function update(Request $request, $id) {
        $category = Category::findOrFail($id);

        $input = $this->validateCategory($request);

        $category->update($input);

        return new CategoryResource($category);
    }

    public function delete($id) {
        $category = Category::findOrFail($id);

        $category->delete();

    return response()->json(['message' => 'category deleted'], 200);
    }

    private function validateCategory($request) {
        return $this->validate($request, [
            'title' => 'required|string|max:255',

            'details' => 'string'
        ]);
    }
}
