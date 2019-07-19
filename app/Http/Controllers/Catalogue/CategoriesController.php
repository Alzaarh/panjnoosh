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
    private const REDIS_KEY = 'categories_view_count';
    
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

        return $this->created('category created');
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateCategory($request);

        $category = Category::findOrFail($id);

        $category->title = $data['title'];

        $category->save();

        return $this->ok('category updated');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        Redis::ZREM(self::REDIS_KEY, 'category:' . $id);
        
        return $this->ok('category deleted');
    }

    public function topCategories()
    {
        $data = [];

        $result = Redis::ZREVRANGE(self::REDIS_KEY, 0, 4, 'WITHSCORES');

        foreach($result as $key => $value)
        {
             $id = explode(':', $key)[1];

             $category = Category::findOrFail($id);

             $object = new \StdClass();
            
             $object->category = $category;

             $object->view_count = $value;

             array_push($data, $object);
        }

        return $this->ok($data);
    }
}
