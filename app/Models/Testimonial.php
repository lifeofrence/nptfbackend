<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'email',
        'position',
        'organization',
        'content',
        'rating',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Scope for approved testimonials
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
