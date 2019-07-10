<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;

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
        return response()->json(['data' => Category::all()], 200);
    }

    public function show($id)
    {
        return response()->json(['data' => Category::findOrFail($id)], 200);
    }

    public function create(Request $request)
    {
        $data = $this->validateCategory($request);

        Category::create($data);

        return response()->json(['data' => 'category created'], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $this->validateCategory($request);

        $category->update($data);

        return response()->json(['data' => 'category updated'], 200);
    }

    public function delete($id)
    {
        
    }
}
