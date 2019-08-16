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

class CategoriesController extends Controller {
    use Errors;
    private const REDIS_KEY = 'categories_view_count';
    //Get all categories
    public function index(Request $request) {
        $this->validatePaginate($request);
        return CategoryResource::collection(Category::paginate($request->query('paginate') ?? null));
    }
    //Check if paginate query is numeric or less than allowed number
    private function validatePaginate(Request $request) {
        if(!preg_match('/^[0-9]*$/', $request->query('paginate')) || $request->query('paginate') > Category::$maxPaginate) {
            throw ValidationException::withMessages([
                'paginate' => [
                    $this->badRequest,
                ],
            ]);
        }
    }
    //Get one category
    public function show(Request $request, $id) {
        $this->validatewithProduct($request);
        $category = Category::findOrFail($id);
        dispatch(new IncrementCategoryViewCount($category->id, self::REDIS_KEY));
        return (new CategoryResource($category));
    }
    //Check if with_product query is boolean
    private function validatewithProduct($request) {
        $this->validate($request, ['with_product' => 'boolean'], ['with_product.*' => $this->badRequest]);
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
