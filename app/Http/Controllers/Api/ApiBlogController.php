<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::all();
        return response(['blogs' => BlogResource::collection($blogs), 'message' => 'Retrieved successfully']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|max:80',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $user_id = auth()->user()->id;

        $data["user_id"] = $user_id;

        $blog = Blog::create($data);

        return response([ 'blog' => new BlogResource($blog), 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return response([ 'blog' => new BlogResource($blog), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response(['message' => 'Deleted']);
    }
}
