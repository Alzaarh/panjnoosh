<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog as BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;

class BlogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'create',
            'update',
            'delete',
        ]]);

        $this->middleware('admin', ['only' => [
            'create',
            'update',
            'delete',
        ]]);
    }
    public function index()
    {
        $blogs = Blog::paginate();
        return BlogResource::collection($blogs);
    }

    public function show($id)
    {
        return new BlogResource(Blog::findOrFail($id));
    }

    public function create(Request $request)
    {
        $this->validateCreate($request);
        $blog = new Blog();
        $blog->title = $request->input('title');
        $blog->short_description = $request->input('short_description');
        $blog->description = $request->input('description');
        $blog->user_id = $request->user->id;
        $this->uploadLogo($request->input('thumbnail'), $blog);
        $blog->save();
        return new BlogResource($blog->refresh());
    }

    public function update(Request $request, $id)
    {
        $this->validateCreate($request);
        $blog = Blog::findOrFail($id);
        $blog->title = $request->input('title');
        $blog->short_description = $request->input('short_description');
        $blog->description = $request->input('description');
        $blog->user_id = $request->user->id;
        $this->uploadLogo($request->input('thumbnail'), $blog);
        $blog->save();
        return new BlogResource($blog->refresh());
    }

    public function delete(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json(['message' => 'ok'], 200);
    }

    private function validateCreate($request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'description' => 'required|string|max:64000',
            'thumbnail' => 'required|string',
        ]);
    }

    private function uploadLogo($logo, $blog)
    {
        $decoder = new Base64ImageDecoder($logo,
            $allowedFormats = ['jpeg', 'png', 'jpg']);
        $name = str_replace('.', '', microtime(true)) . '.' . $decoder->getFormat();
        file_put_contents(storage_path() . '/app/' . $name, $decoder->getDecodedContent());
        $blog->thumbnail = 'storage/' . $name;
    }
}
