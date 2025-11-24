<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Log; 
use App\DTOs\DummyJson\QuoteDTO;


class DummyJsonService
{
    protected string $baseUrl;
    protected HttpClient $http;
    

    public function __construct(HttpClient $http)
    {
        $this->baseUrl = 'https://dummyjson.com/';
        $this->http = $http;
    }

    
    public function getCustomImage(string $text, int $width, int $height, string $bg = '282828', string $fontFamily = 'pacifico'): ?array
    {
        $slug = sprintf('image/%dx%d/%s?fontFamily=%s&text=%s', $width, $height, $bg, urlencode($fontFamily), urlencode($text));
        $fullUrl = $this->baseUrl . $slug;
        try {
            $response = $this->http->withoutVerifying()->get($fullUrl);

            if ($response->successful()) {
                return [
                    'body' => $response->body(),
                    'content_type' => $response->header('Content-Type') ?? 'image/png',
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed img: ' . $e->getMessage(), ['url' => $fullUrl]);
            return null;
        }
    }

     public function getRandomQuote()
    {
        
        $slug = 'quotes/random';

        try {
            
            //NEVER USE THIS IN PRODUCTION OR ANY PLACE 
            //NEVEEEEEEEEEEEEER
            $response = $this->http->withoutVerifying()->get($this->baseUrl . $slug);
            //NEVEEEEEEEEEEEEE

            
            if ($response->successful()) {
                $quote = QuoteDTO::fromApiData($response->json());
                return $quote; //now quote is an instance of QuoteDTO
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Failed to search quote: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}