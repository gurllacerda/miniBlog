<?php

namespace App\DTOs\DummyJson;


class QuoteDTO
{
    // constructor property promotion
    public function __construct(
        public readonly int $id,
        public readonly string $quote,
        public readonly string $author
    ){}

    public static function fromApiData(array $data): self
    {
        return new self(
            id: $data['id'],
            quote: $data['quote'],
            author: $data['author']
        );
    }
}