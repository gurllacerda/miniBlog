<?php

namespace App\Traits;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;


trait Likeable
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function like($userId)
    {
        $userId = $userId ?: Auth::id();
        if (!$this->isLikedByUser($userId)) {
            $this->likes()->create(['user_id' => $userId]);
        }
    }

    public function unlike($userId)
    {
        $userId = $userId ?: Auth::id();
        if ($this->isLikedByUser($userId)) {
            $this->likes()->where('user_id', $userId)->delete();
        }
    }
}