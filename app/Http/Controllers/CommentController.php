<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Post;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post )
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $data['post_id'] = $post->id;

        $comment = Comment::create($data);

        //eager loading, in other words, load the user in just 1 query
        $comment->load('user');

        return response()->json([
            'id' => $comment->id,
            'body' => $comment->body,
            'user_name' => $comment->user->name ?? 'Usuário',
            'created_at' => $comment->created_at->diffForHumans(),
        ], 201);
    }

    
    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    //put the parameters in the correct sequence, equals do routes file (EVEN WHEN YOU DONT USE)
    public function update(UpdateCommentRequest $request, Post $post, Comment $comment)
    {
        $validatedData = $request->validated();
        $comment->update($validatedData);
        $comment->load('user'); //eager loading

        //response has a factory tha will make and return the response object type that you wish 
        return response()->json([//json for the js in the show file 
            'id' => $comment->id,
            'body' => $comment->body,
            'user_name' => $comment->user->name ?? 'Usuário',
            'updated_at' => $comment->updated_at->diffForHumans(),
        ], 200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();
        // dd($post);
        return redirect()->route('posts.show', ['post'=>$post]);
    }
}
