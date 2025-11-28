<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
   protected $fillable = ['user_id'];

   public function user(): BelongsTo    
   {
         return $this->belongsTo(User::class);
   }

   //basically say that this model can belong to more than one model on a single association.
   public function likeable()
   {
         return $this->morphTo();
   }
}
