<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmark extends Model
{
    /** @use HasFactory<\Database\Factories\BookmarkFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'url', 'user_id'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
