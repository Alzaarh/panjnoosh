<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Jobs\IncrementCategoryViewCount;
use Illuminate\Validation\ValidationException;
use App\Utils\Errors;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource;

class CategoriesController extends Controller {
    use Errors;
    private const REDIS_KEY = 'categories_view_count';
    //Constructor
    public function __construct() {
        $this->middleware('auth', ['only' => ['create']]);
        $this->middleware('is.admin', ['only' => ['create']]);
    }
    //Get all categories
    public function index(Request $request) {
        return CategoryResource::collection(Category::paginate());
    }
    //Get one category
    public function show($id) {
        $category = Category::findOrFail($id);
        dispatch(new IncrementCategoryViewCount($category->id, self::REDIS_KEY));
        return (new CategoryResource($category));
    }
    //Get all products for a category
    public function indexProducts(Request $request, $id) {
        $category = Category::findOrFail($id);
        dispatch(new IncrementCategoryViewCount($category->id, self::REDIS_KEY));
        return ProductResource::collection($category->products);
    }
    //Create a category
    public function create(Request $request) {
        $validatedData = $this->validateCreate($request);
        $categoryData = $this->transform($validatedData);
        $category = Category::create($categoryData);
        return (new CategoryResource($category));
    }
    //Validate user input for creating category
    private function validateCreateAndUpdate(Request $request) {
        $rules = [
            'title' => 'required|string|max:255',
        ];
        return $this->validate($request, $rules, ['title.*' => $this->badTitle]);
    }
    //Update a category
    public function update(Requets $request, $id) {
        $category = Category::findOrFail($id);
        $categoryData = $this->transform($this->validateCreateAndUpdate($request));
        $category->update($categoryData);
        return (new CategoryResource($category));
    }
    //Soft delete a category
    public function delete($id) {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['data' => ['message' => 'category deleted']], 200);
    }
    //Transform data to database accepted values
    private function transform(array $data) {
        return [
            'title' => $data['title'],
        ];
    }
    //Get most viewed categories
    public function mostViewedCategories() {
        $mostViewedCategories = [];
        $mostViewedCategoriesIDs = Redis::ZREVRANGE(self::REDIS_KEY, 0, 9, 'WITHSCORES');
        foreach($mostViewedCategoriesIDs as $categoryID => $categoryView) {
             $category = Category::find($categoryID);
             $category->view_count = $categoryView;
             array_push($mostViewedCategories, $category);
        }
        return CategoryResource::collection(collect($mostViewedCategories));
    }
}
