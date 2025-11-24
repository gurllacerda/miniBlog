<?php

namespace App\Http\Controllers;

use App\Services\DummyJsonService;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Comment;
use App\Services\NewsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 


class PostController extends Controller
{
    protected DummyJsonService $dummyJsonService;
    protected NewsService $newsService;

    public function __construct(DummyJsonService $dummyJsonService, NewsService $newsService)
    {
        $this->dummyJsonService = $dummyJsonService;
        $this->newsService = $newsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //remeber that the getRandomQuote method is insecure
        
        //app is the container injecting the instance 
        //$service = app(\App\Services\DummyJsonService::class);
        $dtoQuote = $this->dummyJsonService->getRandomQuote();
        

        $posts = Post::latest()->with(['user'])->get();
        return view("post.index",[
            'quote' => $dtoQuote, //dtoQuote just to make clear it's a DTO
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        Post::create($data);

        return redirect()->route('posts');

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // $service = app(\App\Services\DummyJsonService::class);
        $comments = Comment::where('post_id', $post->id)->latest()->get();
        $customImage = $this->dummyJsonService->getCustomImage($post->title, 280, 140);
        return view('post.show', ['post' => $post, 'img' => $customImage, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('post.edit', ['post' => $post ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validatedData = $request->validated();

        $post->update($validatedData);

        return redirect()->route('posts.show', ['post'=> $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect('/posts');
    }

    public function generateIdea()
    {
        $newsData = $this->newsService->getTechPost();
        if (is_string($newsData)) { // when the service got an error (came from the service a string)
            Log::error('NewsService returned error: '.$newsData);
            return response()->json(['error' => 'Service error'], 500);
        }

        $article = $newsData['articles'][0] ?? null;
        
        if (!$article) {
            Log::warning('NewsService: no articles found', (array) $newsData);
            return response()->json(['error' => 'No articles found'], 404);
        }

        return response()->json([ //return json for the js in the blade
            'title' => $article['title'] ?? '',
            'description' => $article['content'] ?? $article['description']   ?? '',
        ]);
    }
}
