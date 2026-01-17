<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'location',
        'start_date',
        'end_date',
        'image_url',
        'image_public_id',
        'impact',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $appends = ['image'];

    // Accessor to get image URL (for backward compatibility with frontend)
    public function getImageAttribute()
    {
        return $this->image_url;
    }
}
