<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Log; 

class NewsService{
    
    protected string $baseUrl;
    protected HttpClient $http;
    protected string $apiKey;
    

    public function __construct(HttpClient $http)
    {
        $this->baseUrl = 'https://newsapi.org/v2/';
        $this->http = $http;
        $this->apiKey = config('services.news.key');
    }

    public function getTechPost()
    {
        try {
            //DONT DO THE WITHOUT VERIFYING THING ANYWHERE, JUST IN THIS PROJECT
             $response = $this->http->withoutVerifying()->get($this->baseUrl . 'top-headlines', [
            'apiKey' => $this->apiKey,
            'category' => 'technology',
            'language' => 'en',
            ]);

        } catch (\Exception $e) {
            Log::warning('Failed to search News: ' . $e->getMessage());
            return $e->getMessage();
        }

        return $response->json();
    }
}