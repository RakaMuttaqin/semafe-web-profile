<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'published_at',
        'status',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
