<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */

    public function index()
    {
        // dd('hit');
        $comments = Comment::latest()->with(['user', 'post'])->get();

        return $comments->toResourceCollection();
    }
    public function store(Request $request)
    {

        dd('hit');
        // $validatedData = $request->validated();
        // $data['user_id'] = Auth::user()->id;
        // $data['post_id'] = $post->id;
        // $comment = Comment::create($validatedData);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
