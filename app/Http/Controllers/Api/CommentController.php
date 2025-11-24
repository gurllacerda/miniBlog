<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::latest()->with(['user', 'post'])->get();
        return $comments->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, string $postId)
    {   
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $data['post_id'] = $postId;
        $comment = Comment::create($data);

        //eager loading, in other words, load the user in just 1 query
        $comment->load('user');

        return $comment->toResource();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $postId, string $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        return $comment->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, string $postId, string $commentId)
    {
        $validatedData = $request->validated();
        $comment = Comment::findOrFail($commentId);
        $comment->update($validatedData);
        return $comment->toResource();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $postId, string $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
        return response()->json(["Comment sucessfully deleted!"], 200);
    }
}
