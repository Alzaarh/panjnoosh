<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Traits\ResponseTrait;
use App\Jobs\IncCategoryViewCountJob;

class CategoriesController extends Controller
{
    use ResponseTrait;

    private function validateCategory(Request $request)
    {
        $rules = [
            'title' => 'bail|required|string',
        ];

        return $this->validate($request, $rules);
    }

    public function index()
    {
        $categories = Category::all();

        return $this->ok($categories);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        dispatch(new IncCategoryViewCountJob('category:' . $category->id));

        return $this->ok($category);
    }

    public function create(Request $request)
    {
        $data = $this->validateCategory($request);

        $category = Category::create($data);

        if(Redis::exists('categories_count'))
        {
            Redis::incr('categories_count');
        }
        Redis::hset('categories', $category->id, json_encode($category));

        return response()->json(['data' => 'category created'], 201);
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateCategory($request);

        $category = Category::findOrFail($id);

        $category->title = $data['title'];

        $category->save();

        Redis::hset('categories', $id, json_encode($category));

        return response()->json(['data' => 'category updated'], 200);
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        if(Redis::exists('categories_count'))
        {
            Redis::decr('categories_count');
        }

        Redis::hdel('categories', $id);

        return response()->json(['data' => 'category deleted'], 200);
    }
}
