<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of news articles.
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Order by published date
        $query->orderBy('published_at', 'desc');

        // Paginate results
        $news = $query->paginate($request->get('per_page', 10));

        return response()->json($news);
    }

    /**
     * Store a newly created news article.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120', // Max 5MB
            'published_at' => 'nullable|date',
        ]);

        $news = new News($validated);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            try {
                $cloudinary = app(CloudinaryService::class);
                $result = $cloudinary->uploadImage($request->file('image'), 'news');
                $news->image_url = $result['url'];
                $news->image_public_id = $result['public_id'];
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload image',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        $news->save();

        return response()->json([
            'message' => 'News article created successfully',
            'data' => $news
        ], 201);
    }

    /**
     * Display the specified news article.
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news);
    }

    /**
     * Update the specified news article.
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'author' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
            'published_at' => 'nullable|date',
        ]);

        $news->fill($validated);

        // Handle image upload to Cloudinary
        if ($request->hasFile('image')) {
            try {
                $cloudinary = app(CloudinaryService::class);

                // Delete old image if exists
                if ($news->image_public_id) {
                    $cloudinary->deleteImage($news->image_public_id);
                }

                // Upload new image
                $result = $cloudinary->uploadImage($request->file('image'), 'news');
                $news->image_url = $result['url'];
                $news->image_public_id = $result['public_id'];
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload image',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        $news->save();

        return response()->json([
            'message' => 'News article updated successfully',
            'data' => $news
        ]);
    }

    /**
     * Remove the specified news article.
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Delete image from Cloudinary if exists
        if ($news->image_public_id) {
            try {
                $cloudinary = app(CloudinaryService::class);
                $cloudinary->deleteImage($news->image_public_id);
            } catch (\Exception $e) {
                // Log error but continue with deletion
                \Log::error('Failed to delete image from Cloudinary: ' . $e->getMessage());
            }
        }

        $news->delete();

        return response()->json([
            'message' => 'News article deleted successfully'
        ]);
    }
}
