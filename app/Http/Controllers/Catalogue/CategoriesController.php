<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoriesController extends Controller
{
    private function validateCategory(Request $request)
    {
        $rules = [
            'title' => 'bail|required|string',
        ];

        return $this->validate($request, $rules);
    }

    public function index()
    {
        if(Redis::exists('categories_count'))
        {
            $data = [];

            $categories = Redis::hvals('categories');

            foreach($categories as $category)
            {
                array_push($data, json_decode($category));
            }

            return response()->json(['data' => $data], 200);
        }

        $categories = Category::all();

        Redis::set('categories_count', $categories->count());

        foreach($categories as $category)
        {
            Redis::hset('categories', $category->id, $category);
        }

        return response()->json(['data' => $categories], 200);
    }

    public function show($id)
    {
        if(Redis::hexists('categories', $id))
        {
            return response()->json(['data' => json_decode(Redis::hget('categories', $id))], 200);
        }

        $category = Category::findOrFail($id);

        Redis::hset('categories', $id, $category);

        return response()->json(['data' => $category], 200);
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
