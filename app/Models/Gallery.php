<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'gallery';

    protected $fillable = [
        'event_name',
        'title',
        'type',
        'media_url',
        'media_public_id',
        'thumbnail_url',
        'thumbnail_public_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected $appends = ['media', 'thumbnail'];

    // Accessor to get media URL (for backward compatibility with frontend)
    public function getMediaAttribute()
    {
        return $this->media_url;
    }

    // Accessor to get thumbnail URL (for backward compatibility with frontend)
    public function getThumbnailAttribute()
    {
        return $this->thumbnail_url;
    }
}
