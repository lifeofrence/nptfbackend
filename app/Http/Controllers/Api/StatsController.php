<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Project;
use App\Models\Gallery;
use App\Models\Testimonial;
use App\Models\Contact;

class StatsController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index()
    {
        return response()->json([
            'news' => News::count(),
            'projects' => Project::count(),
            'gallery' => Gallery::count(),
            'testimonials' => Testimonial::count(),
            'contacts' => Contact::count(),
        ]);
    }
}
