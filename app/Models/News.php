<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'author',
        'category',
        'image_url',
        'image_public_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = ['image'];

    // Accessor to get image URL (for backward compatibility with frontend)
    public function getImageAttribute()
    {
        return $this->image_url;
    }
}
