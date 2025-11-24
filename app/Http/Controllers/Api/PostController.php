<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;

//resource is a way to transform the model data before sending it as a response
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->with(['user'])->get();
        
        // return PostResource::collection($posts);
        return $posts->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $post = Post::create($data);

        return $post->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        // return new PostResource($user);
        return $post->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $post = Post::findOrFail($id);
        $post->update($validatedData);

        return $post->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.'], 200);
    }
}
